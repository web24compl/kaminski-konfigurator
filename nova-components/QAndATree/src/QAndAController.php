<?php

declare(strict_types=1);

namespace Configurator\QAndATree;

use App\Http\Controllers\Controller;
use App\Models\QAndATreeItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class QAndAController extends Controller
{
    public function tree(): JsonResponse
    {
        $tree = QAndATreeItem::query()->with(['answers'])->get()->collect();

        $buildTree = $this->buildTree($tree);
        return response()->json($buildTree);
    }


    private function buildTree(Collection $elements, int $parentId = null): array
    {
        $branch = [];

        /** @var QAndATreeItem $element */
        foreach ($elements as $element) {
            if ($element->parent_question_id === $parentId) {
                $newElement = [
                    'id' => $element->id,
                    'question_text' => $element->question_text,
                    'answer_text' => $element->answer_text,
                ];

                $children = $this->buildTree($elements, $element->id);

                if (!empty($children)) {
                    $element['children'] = $children;
                    $newElement['children'] = $children;
                }

                if (!$parentId) {
                    $branch['children'] = $newElement;
                } else {
                    $branch[] = $newElement;
                }

            }
        }

        return $branch;
    }
}
