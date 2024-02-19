<?php

namespace Web24\Export;

use App\Models\ChatResponse;
use Laravel\Nova\Card;

class Export extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/2';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'export';
    }

    public function export()
    {
        $chatResponses = ChatResponse::all();

        return $this->withMeta(['export' => $chatResponses]);
    }
}
