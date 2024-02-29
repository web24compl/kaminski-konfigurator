<?php

declare(strict_types=1);

namespace Configurator\QAndATree\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\QAndATreeItem;
use Configurator\QAndATree\Http\Requests\SaveTreeItemRequest;
use Configurator\QAndATree\Http\Requests\UpdateTreeItemRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class QAndAController extends Controller
{
    public function tree(): JsonResponse
    {
        $tree = QAndATreeItem::query()->with(['answers', 'parentQuestion'])->get()->collect();

        $buildTree = $this->buildTree($tree);

        return response()->json(['tree' => $buildTree]);
    }

    public function create(SaveTreeItemRequest $request): JsonResponse
    {
        $input = $request->validated();

        $item = new QAndATreeItem($input);
        $item->save();

        return response()->json();
    }

    public function update(UpdateTreeItemRequest $request, QAndATreeItem $item): JsonResponse
    {
        $item->update([
            'question_text' => $request->get('question_text'),
            'answer_text' => $request->get('answer_text'),
        ]);

        return response()->json();
    }

    public function delete(QAndATreeItem $item): JsonResponse
    {
        $item->delete();

        return response()->json();
    }

    private function buildTree(Collection $elements, int $parentId = null): array
    {
        $branch = [];

        /** @var QAndATreeItem $element */
        foreach ($elements as $element) {
            if ($element->parent_question_id === $parentId) {
                $newElement = $element;
                $newElement['show_input_question_text'] = !empty($element->question_text);

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
