<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Instruction;
use App\Models\InstructionChildren;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Exception;

class InstructionController extends Controller
{
    public function index()
    {
        // Fetch all instructions, eager loading the children
        $instructions = Instruction::with('children')->whereNull('parent_id')->get();

        // Format the data
        $formattedInstructions = $this->formatInstructions($instructions);

        return response()->json($formattedInstructions);
    }

    private function formatInstructions($instructions)
    {
        return $instructions->map(function ($instruction) {
            return [
                'id' => $instruction->id,
                'name' => $instruction->name,
                'description' => $instruction->description,
                'children' => $this->getChildren($instruction->children),
            ];
        });
    }

    private function getChildren($children)
    {
        return $children->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => $child->name,
                'description' => $child->description,
                'children' => $this->getChildren($child->children),
            ];
        });
    }


    public function show($id)
    {
        $instructions = Instruction::with('children')->where('id', $id)->get();

        // Format the data
        $formattedInstructions = $this->formatInstructions($instructions);

        return response()->json($formattedInstructions);
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $validatedData = $request->validate([
            'instructions' => 'required|array',
            'instructions.*.name' => 'required|string|max:255',
            'instructions.*.description' => 'nullable|string',
            'instructions.*.children' => 'nullable|array',
        ]);

        foreach ($validatedData['instructions'] as $item) {
            if ($id) {
                $instruction = Instruction::findOrFail($id);
                $instruction->update([
                    'name' => $item['name'],
                    'description' => "",
                    'parent_id' => null,
                ]);
            } else {
                $instruction = Instruction::create([
                    'name' => $item['name'],
                    'description' => "",
                    'parent_id' => null,
                ]);
            }
            foreach ($instruction->children as $child) {
                $child->delete(); // Delete the child
            }
            $this->saveChildren($instruction, $item['children']);
        }

        return response()->json(['message' => 'Instructions saved successfully']);
    }

    private function saveChildren($parentInstruction, $children)
    {
        foreach ($children as $child) {
            $childInstruction = Instruction::create([
                'name' => $child['name'],
                'description' => $child['description'] ?? "",
                'parent_id' => $parentInstruction->id,
            ]);

            if (!empty($child['children'])) {
                $this->saveChildren($childInstruction, $child['children']);
            }
        }
    }
}
