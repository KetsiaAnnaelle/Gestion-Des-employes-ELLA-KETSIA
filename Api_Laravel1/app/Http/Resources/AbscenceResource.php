<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbscenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request):array
    {
        return [
            'dateAbs'=> $this->dateAbs,
            'nbreHeureAbs'=> $this->nbreHeureAbs,
            'typeAbs'=> $this->typeAbs,
            'motifAbs'=> $this->motifAbs,
            'formation' => $this->formation,
            'etudiant' => $this->etudiant,
            'cour' => $this->cour,
        ];

    }
}
