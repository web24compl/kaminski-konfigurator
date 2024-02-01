<?php

use App\Models\Page;
use App\Models\Setting;
use App\Traits\DashboardModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

if (!function_exists('uses_trait')) {
    function uses_trait($class, $trait)
    {
        return in_array($trait, class_uses($class));
    }
}

if (!function_exists('enabled_languages')) {
    function enabled_languages()
    {
        $languages = getLocales();
        $key = array_search(App::getLocale(), $languages);

        if ($key === false) {
            return $languages;
        }

        $sorted = $languages[$key];
        unset($languages[$key]);
        array_unshift($languages, $sorted);

        return $languages;
    }
}

if (!function_exists('modelUrl')) {
    function modelUrl($modelType, $modelName = null, $separator = ',')
    {
        if ($modelName === null) {
            $modelName = $modelType;
            $modelType = null;
        }
        return ($modelType !== null ? "{$modelType}{$separator}" : "") . $modelName;
    }
}

if (!function_exists('getMedia')) {
    function getMedia($object, $name, $conversion = null)
    {
        $media = $object->getMedia($name)->first();
        if (!$media) {
            return '';
        }
        return $conversion === null ? $media->getFullUrl() : $media->getUrl($conversion);
    }
}

if (!function_exists('safe_number_format')) {
    function safe_number_format($num, $decimals = 0, $decimal_separator = '.', $thousands_separator = ',')
    {
        if (!is_numeric($num)) {
            return $num;
        }

        return number_format($num, $decimals, $decimal_separator, $thousands_separator);
    }
}

if (!function_exists('get_number_sign')) {
    function get_number_sign($number): string
    {
        if ($number > 0) {
            return '+';
        } elseif ($number < 0) {
            return '-';
        }

        return '';
    }
}

if (!function_exists('isSiteMultiLanguage')) {
    function isSiteMultiLanguage(string $area)
    {
        return config('localization.' . $area);
    }
}

if (!function_exists('getPage')) {
    function getPage(string $pageType, ?string $locale = null): ?Page
    {
        if (is_null($locale)) {
            $locale = App::getLocale();
        }

        $setting = Setting::getByKey('page_' . $pageType);

        return $setting?->getLangPage($locale);
    }
}


if (!function_exists('parsePhoneNumber')) {
    function parsePhoneNumber(?string $phoneNumber): ?string
    {
        return preg_replace('/[^\+\d]/', '', $phoneNumber);
    }
}

if (!function_exists('itemRoute')) {
    function itemRoute(Model $item): ?string
    {
        if (array_search($item::class, config('front.items')) === false) {
            return null;
        }

        if (!uses_trait($item, DashboardModel::class)) {
            return null;
        }

        /** @var \App\Traits\DashboardModel $item */
        if (!$item->isFrontItem()) {
            return null;
        }

        /** @var \App\Traits\DashboardModel|\App\Traits\FrontItem $item */
        if ($item->isMultilingual()) {
            $locale = $item->language;
        } else {
            $locale = App::getLocale();
        }

        $category = $item->getFrontItemCategory($locale);
        if (is_null($category)) {
            return null;
        }

        $slug = $item->slug;

        if (config('localization.front')) {
            return route('item', compact('locale', 'category', 'slug'));
        }

        return route('item', compact('category', 'slug'));
    }
}

if (!function_exists('pageRoute')) {
    function pageRoute(string $pageType, ?string $locale = null): ?string
    {
        $page = getPage($pageType, $locale);

        if (is_null($page)) {
            return null;
        }

        return route('page', $page->slug);
    }
}

if (!function_exists('cmfSettings')) {
    function cmfSettings(): Collection
    {
        static $settings;

        if (!isset($settings)) {
            $settings = Setting::getAll()
                ->mapWithKeys(fn(Setting $setting) => [$setting->key => $setting->value]);
        }

        return $settings;
    }
}

if (!function_exists('cmfSetting')) {
    function cmfSetting(string $key, mixed $default = null): mixed
    {
        return cmfSettings()->get($key) ?? $default;
    }
}

if (!function_exists('price')) {
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function price(?int $value, string $currency = null): ?string
    {
		if (is_null($value)) {
			return null;
		}

		return app()
			->make(NumberFormatter::class, ['locale' => App::getLocale(), 'style' => NumberFormatter::CURRENCY])
			->formatCurrency(
				round($value / 100, 2),
				$currency ?? 'PLN'
			);
	}
}

if (!function_exists('routeCourse')) {
	function routeCourse(string $name, array $params = []): string
	{
        $courseCode = request()->route('courseCode');
        if (is_null($courseCode)) {
            return route('home');
        }

		return route($name, [...$params, 'courseCode' => $courseCode]);
	}
}
