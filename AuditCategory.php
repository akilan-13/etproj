<?php

namespace App\Http\Controllers\settings\branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditCategoryModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AuditCategory extends Controller {
    protected static $branch_id = 1;

    public function index() {

        $id=3;
        $audit_data = AuditCategoryModel::where('sno', $id)
        ->first();
 
        $questions = DB::table('za_audit_question')
        ->select('za_audit_question.audit_question_name as question_name',
                'za_audit_question.audit_question_option',
                'za_audit_question.audit_question_type',
                'za_audit_question.audit_category_id as department_id',
                'za_audit_question_save.score',
                'za_audit_question_save.audit_question_answer')
        ->leftJoin('za_audit_question_save', 'za_audit_question.sno', '=', 'za_audit_question_save.department_question_id')
        ->where('za_audit_question.status', 0)
        ->where('za_audit_question.audit_category_id', $id)
        ->get();
             
             foreach ($questions as $singleQuestion) {
                 $options = json_decode($singleQuestion->audit_question_option);
                 $singleQuestion->audit_question_option =  $options;
             }
 
        $audit_data->questions = $questions;
    //    return $audit_data;

        $Department = AuditCategoryModel::where('status', '!=', 2 )->orderBy( 'sno', 'desc' )->get();
        $pageConfigs = [ 'myLayout' => 'default' ];
        return view( 'content.settings.branch.audit_category.audit_category_list', [
            'Department' => $Department,
            'pageConfigs' => $pageConfigs
        ] );
    }

    public function List() {
        $Department = AuditCategoryModel::where( 'status', '!=', 2 )->where( 'branch_id', self::$branch_id )->orderBy( 'sno', 'desc' )->get();

        return response( [
            'status' => 200,
            'message' => null,
            'error_msg' => null,
            'data' => $Department
        ], 200 );
    }

    public function Add( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'audit_category_name' => 'required|max:255'
        ] );
        if ( $validator->fails() ) {
            return response( [
                'status' => 401,
                'message' => 'Incorrect format input feilds',
                'error_msg' => $validator->messages()->get( '*' ),
                'data' => null,
            ], 200 );
        } else {

            $audit_category_name = $request->audit_category_name;
            $audit_category_desc = $request->audit_category_desc;
            $user_id = 1;
            $chk = AuditCategoryModel::where( 'audit_category_name', ucwords( $audit_category_name ) )->where( 'branch_id', self::$branch_id )->where( 'status', '!=', 2 )->first();

            if ( $chk ) {

                session()->flash( 'toastr', [
                    'type' => 'error',
                    'message' => 'Already Audit Category is exist!'
                ] );
                return redirect()->back();
            } else {
                $category_check = AuditCategoryModel::where( 'status', '!=', 2 )->orderBy( 'sno', 'desc' )->first();

                if ( !$category_check ) {

                    $year = substr( date( 'y' ), -2 );
                    $audit_category_id = 'AC-0001/' . $year;
                } else {

                    $data = $category_check->audit_category_id;
                    $slice = explode( '/', $data );
                    $result = preg_replace( '/[^0-9]/', '', $slice[ 0 ] );

                    $next_number = ( int ) $result + 1;
                    $request = sprintf( 'AC-%04d', $next_number );

                    $year = substr( date( 'y' ), -2 );
                    $audit_category_id = $request . '/' . $year;
                }

                $add_department = new AuditCategoryModel();
                $add_department->audit_category_id = $audit_category_id;
                $add_department->audit_category_name = Ucfirst( $audit_category_name );
                $add_department->audit_category_desc = $audit_category_desc;
                $add_department->branch_id = self::$branch_id;
                $add_department->created_by = $user_id;
                $add_department->updated_by = $user_id;

                $add_department->save();

                if ( $add_department ) {
                    // If category added successfully, return success response and display Toastr message
                    session()->flash( 'toastr', [
                        'type' => 'success',
                        'message' => 'Audit Category added Successfully!'
                    ] );
                } else {
                    session()->flash( 'toastr', [
                        'type' => 'error',
                        'message' => 'Could not add the Audit Category!'
                    ] );
                }
            }
            return redirect()->back();
        }
    }

    public function Edit( $id ) {
        $editdepartment = AuditCategoryModel::where( 'sno', $id )->first();

        return view( 'content.settings.branch.department.department_list', [
            'editdepartment' => $editdepartment,
            'id' => $id,
        ] );
    }

    public function Update( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'audit_category_name' => 'required|max:255',

        ] );

        if ( $validator->fails() ) {
            return response( [
                'status' => 401,
                'message' => 'Incorrect format input feilds',
                'error_msg' => $validator->messages()->get( '*' ),
                'data' => null,
            ], 200 );
        } else {

            $audit_category_name = $request->audit_category_name;
            $audit_category_desc = $request->audit_category_desc;
            $audit_category_id = $request->audit_category_id;

            $upd_AuditCategory = AuditCategoryModel::where( 'sno', $audit_category_id )->first();

            $chk = AuditCategoryModel::where( 'audit_category_name', ucwords( $audit_category_name ) )->where( 'branch_id', self::$branch_id )->where( 'sno', '!=', $audit_category_id )->where( 'status', '!=', 2 )->first();

            if ( $chk ) {
                session()->flash( 'toastr', [
                    'type' => 'error',
                    'message' => 'Already Department is exist!'
                ] );
                return redirect()->back();
            } else {

                $upd_AuditCategory->audit_category_name = Ucfirst( $audit_category_name );
                $upd_AuditCategory->audit_category_desc = $audit_category_desc;
                $upd_AuditCategory->branch_id = self::$branch_id;
                $upd_AuditCategory->update();

                if ( $upd_AuditCategory ) {
                    // If category added successfully, return success response and display Toastr message
                    session()->flash( 'toastr', [
                        'type' => 'success',
                        'message' => 'Department Update Successfully!'
                    ] );
                } else {
                    session()->flash( 'toastr', [
                        'type' => 'error',
                        'message' => 'Could not Update the Department!'
                    ] );
                }
            }
        }
        return redirect()->back();
    }

    public function Delete( $id ) {
        $upd_AuditCategory = AuditCategoryModel::where( 'sno', $id )->first();
        $upd_AuditCategory->status = 2;
        $upd_AuditCategory->Update();

        return response( [
            'status' => 200,
            'message' => 'Successfully Deleted!',
            'error_msg' => null,
            'data' => null,
        ], 200 );
    }

    public function Status( $id, Request $request ) {

        $upd_AuditCategory = AuditCategoryModel::where( 'sno', $id )->first();
        $upd_AuditCategory->status = $request->input( 'status', 0 );
        $upd_AuditCategory->update();

        return response( [
            'status' => 200,
            'message' => 'Successfully Status Updated!',
            'error_msg' => null,
            'data' => null,
        ], 200 );
    }


    public function AuditQuestionsList(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'audit_id' => 'required|integer',
            'in_time'=>'required|string',
        ]);


        // Extract the department_ids from the request
        $audit_id = $request->input('audit_id');

        $in_time=$request->in_time;

        // return $in_time;

        $audit_staff_image_base64 = $request->input('audit_staff_image');  // base64 image string

        if($audit_staff_image_base64){
            // Validate the base64 string and convert it to an image
            // if (preg_match('/^data:image\/(\w+);base64,/', $audit_staff_image_base64, $type)) {
            if ($audit_staff_image_base64) {
                $base64_image = substr($audit_staff_image_base64, strpos($audit_staff_image_base64, ',') + 1);
                $decoded_image = base64_decode($base64_image);
                // if ($decoded_image === false) {
                //     return response()->json([
                //         'status' => 400,
                //         'message' => 'Invalid base64 string.',
                //     ], 400);
                // }
            } 
        
            // Generate a unique filename for the image
            $imageName = 'audit_' . $audit_id . '.png'; // or use any other unique naming convention
        
            // Define the path to store the image in the public directory
            $imagePath = public_path('audit_staff_image/' . $imageName);
        
            // Ensure the directory exists
            if (!file_exists(public_path('audit_staff_image'))) {
                mkdir(public_path('audit_staff_image'), 0777, true); // Create directory if not exists
            }
        
            // Save the image to the file system
            file_put_contents($imagePath, $decoded_image);
        
            // Now save the file path to the database
            $audit_data = BranchAuditModel::where('sno', $audit_id)->first();
            if ($audit_data) {
                $audit_data->audit_staff_image =$imageName; // Save the relative path
                $audit_data->audit_start_time =$in_time; // Save the relative path
                $audit_data->update();
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Audit not found.',
                ], 404);
            }
        }

        $audit_data = BranchAuditModel::select('za_branch_audit.department_ids')
            ->where('za_branch_audit.sno',$audit_id)
            ->first();

        $department_ids = json_decode($audit_data->department_ids, true);

        // Start timing
        $startTime = microtime(true);


        // Fetch categories
        $departments = collect(AuditCategoryModel::whereIn('sno', $department_ids)->orderBy('audit_category_name', 'asc')->get()->keyBy('sno'));

        // Fetch questions and join with departments
        $questions = collect(
            AuditQuestionModel::whereIn('za_audit_question.audit_category_id', $department_ids)
                ->where('za_audit_question.status', 0)
                ->join('za_audit_category', 'za_audit_category.sno', '=', 'za_audit_question.audit_category_id')
                ->select('za_audit_category.sno as audit_category_id', 'za_audit_category.audit_category_name', 'za_audit_question.*')
                ->orderBy('za_audit_question.sno', 'asc')
                ->get()
                ->groupBy('audit_category_id')
        );
        // return $questions;
        // Format the response
        $response = $departments->map(function ($category) use ($questions) {
            return [
                'audit_department_id'   => $category->sno,
                'department_name' => $category->audit_category_name,
                'question'      => $questions->get($category->sno, collect([]))
                    ->map(function ($question) {
                        return [
                            'department_question_id' => strval($question->sno),
                            'audit_department_id' => strval($question->audit_category_id),
                            'audit_question_name' => strval($question->audit_question_name),
                            'audit_question_type' => strval($question->audit_question_type),
                            // Ensure proper JSON handling
                            'audit_question_option' => json_decode($question->audit_question_option, true) ?? [],
                            'created_by' => strval($question->created_by),
                            'created_at' => $question->created_at->toISOString(),
                            'updated_by' => strval($question->updated_by),
                            'updated_at' => $question->updated_at->toISOString(),
                            'status' => strval($question->status),
                        ];
                    })
                    ->toArray()
            ];
        });



        // End timing
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Return the response
        return response()->json([
            'status'    => 200,
            'message'   => 'Audit Departmet Question List',
            'error_msg' => null,
            'data'      => $response,
            'execution_time' => $executionTime . ' seconds'
        ], 200);
    }

    public function AuditCategoryQuestions($id,Request $request)
    {  

        $audit_data = AuditCategoryModel::where('sno', $id)
        ->first();
 
        $questions = DB::table('za_audit_question')
        ->select('za_audit_question.audit_question_name as question_name',
                'za_audit_question.audit_question_type',
                'za_audit_question.audit_question_option')
        ->where('za_audit_question.status', 0)
        ->where('za_audit_question.audit_category_id', $id)
        ->get();
             
        foreach ($questions as $singleQuestion) {
            $options = json_decode($singleQuestion->audit_question_option);
        
            $optionValues = [];
            $scores = [];
        
            foreach ($options as $option) {
                $optionValues[] = $option->value;
                $scores[] = isset($option->score[0]) ? (string) $option->score[0] : "0";
            }
        
            // Add as decoded arrays
            $singleQuestion->options = $optionValues;
            $singleQuestion->scores = $scores;
        
            // Optionally remove original
            unset($singleQuestion->audit_question_option);
        }
 
        $audit_data->questions = $questions;
        
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => null,
            'error_msg' => null,
            'data' => $audit_data ,
        ], 200);
    }

}
