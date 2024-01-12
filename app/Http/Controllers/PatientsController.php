<?php

namespace App\Http\Controllers;

use App\Http\ApiResources\PatientResource;
use App\Models\GynecologicalObstetricPregnancies;
use App\Models\HereditaryFamilyHistory;
use App\Models\NonPathologicalHistory;
use App\Models\PathologicalHistory;
use App\Models\Patient;
use App\Models\Person;
use App\Models\PhysicalPerson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class PatientsController extends RestController
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = PatientResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse // TODO: Implementar Patient form request validate
    {
        try {
            DB::beginTransaction();
            $physical_people = PhysicalPerson::create($request->all());

            $request['personable_type'] = $physical_people::class;
            $request['personable_id'] = $physical_people->id;

            $person = Person::query()->create($request->all());

            $request['person_id'] = $person->id;
            $patient = Patient::create($request->all());

            $hereditaryFamilyHistories = $request->post("hereditary_family_histories");

            if (false === empty($hereditaryFamilyHistories)) {
                HereditaryFamilyHistory::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'observations' => $hereditaryFamilyHistories['observations'],
                    ],
                );
            }

            $nonPathologicalHistories = $request->post("non_pathological_histories");

            if (false === empty($nonPathologicalHistories)) {
                NonPathologicalHistory::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'room' => $nonPathologicalHistories['room'],
                        'hygiene_habits' => $nonPathologicalHistories['hygiene_habits'],
                        'feeding' => $nonPathologicalHistories['feeding'],
                    ],
                );
            }

            $gynecologicalObstetricPregnancies = $request->post("gynecological_obstetric_pregnancies");

            if (false === empty($gynecologicalObstetricPregnancies)) {
                GynecologicalObstetricPregnancies::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'menarche' => $gynecologicalObstetricPregnancies['menarche'],
                        'IVSA' => $gynecologicalObstetricPregnancies['ivsa'],
                        'number_of_partners' => $gynecologicalObstetricPregnancies['number_of_partners'],
                        'pregnancies_G_P_C_A_O' => $gynecologicalObstetricPregnancies['pregnancies_G_P_C_A_O'],
                        'contraceptive_method_id' => $gynecologicalObstetricPregnancies['contraceptive_method_id'],
                    ],
                );
            }

            $pathologicalHistories = $request->post("pathological_histories");

            if (false === empty($pathologicalHistories)) {
                PathologicalHistory::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'infectious_diseases' => $pathologicalHistories['infectious_diseases'],
                        'chronic_degenerative_diseases' => $pathologicalHistories['chronic_degenerative_diseases'],
                        'allergies' => $pathologicalHistories['allergies'],
                        'surgical_interventions' => $pathologicalHistories['surgical_interventions'],
                        'traumatism' => $pathologicalHistories['traumatism'],
                        'transfusions' => $pathologicalHistories['transfusions'],
                        'seizures' => $pathologicalHistories['seizures'],
                        'addictions' => $pathologicalHistories['addictions'],
                        'hospitalizations' => $pathologicalHistories['hospitalizations'],
                    ],
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Paciente registrado correctamente.',
                'data' => $patient,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => 'Error al registrar el paciente.',
                'error' => $th->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient): JsonResponse
    {
        try {
            DB::beginTransaction();
            $physical_people = PhysicalPerson::query()->find($patient->person->personable_id);
            $physical_people->update($request->all());

            $person = Person::query()->find($patient->person_id);
            $person->update($request->all());

            $patient->update($request->all());

            $hereditaryFamilyHistories = $request->post("hereditary_family_histories");

            if (false === empty($hereditaryFamilyHistories)) {
                HereditaryFamilyHistory::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'observations' => $hereditaryFamilyHistories['observations'],
                    ],
                );
            }

            $nonPathologicalHistories = $request->post("non_pathological_histories");

            if (false === empty($nonPathologicalHistories)) {
                NonPathologicalHistory::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'room' => $nonPathologicalHistories['room'],
                        'hygiene_habits' => $nonPathologicalHistories['hygiene_habits'],
                        'feeding' => $nonPathologicalHistories['feeding'],
                    ],
                );
            }

            $gynecologicalObstetricPregnancies = $request->post("gynecological_obstetric_pregnancies");

            if (false === empty($gynecologicalObstetricPregnancies)) {
                GynecologicalObstetricPregnancies::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'menarche' => $gynecologicalObstetricPregnancies['menarche'],
                        'IVSA' => $gynecologicalObstetricPregnancies['ivsa'],
                        'number_of_partners' => $gynecologicalObstetricPregnancies['number_of_partners'],
                        'pregnancies_G_P_C_A_O' => $gynecologicalObstetricPregnancies['pregnancies_G_P_C_A_O'],
                        'contraceptive_method_id' => $gynecologicalObstetricPregnancies['contraceptive_method_id'],
                    ],
                );
            }

            $pathologicalHistories = $request->post("pathological_histories");

            if(false === empty($pathologicalHistories)) {
                PathologicalHistory::query()->updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                    ],
                    [
                        'infectious_diseases' => $pathologicalHistories['infectious_diseases'],
                        'chronic_degenerative_diseases' => $pathologicalHistories['chronic_degenerative_diseases'],
                        'allergies' => $pathologicalHistories['allergies'],
                        'surgical_interventions' => $pathologicalHistories['surgical_interventions'],
                        'traumatism' => $pathologicalHistories['traumatism'],
                        'transfusions' => $pathologicalHistories['transfusions'],
                        'seizures' => $pathologicalHistories['seizures'],
                        'addictions' => $pathologicalHistories['addictions'],
                        'hospitalizations' => $pathologicalHistories['hospitalizations'],
                    ],
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Paciente actualizado correctamente.',
                'data' => $patient,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => 'Error al actualizar el paciente.',
                'error' => $th->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
