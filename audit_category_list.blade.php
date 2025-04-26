@extends('layouts/layoutMaster')

@section('title', 'Department')


@section('vendor-style')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/nouislider/nouislider.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/nouislider/nouislider.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/sliders.js'])
@endsection

@section('content')
<!-- Users List Table -->
<div class="row">
    <div class="col-xl-12">
        <div class="nav-align-top mb-2">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a href="{{ url('/settings/branch_type/branch_type_list') }}" type="button" class="nav-link text-capitalize ">Branch
                        Category</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/document_type/document_type_list') }}" type="button" class="nav-link text-capitalize ">Document
                        Type</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/department/department_list') }}" type="button" class="nav-link text-capitalize ">Department
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/sub_department/sub_department_list') }}" type="button" class="nav-link text-capitalize ">Sub Department</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/ipconfig/ipconfig') }}" type="button" class="nav-link text-capitalize ">White Listed IP's</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/branch/audit_staff_list') }}" type="button" class="nav-link text-capitalize ">Audit Staff
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/branch/audit_category_list') }}" type="button" class="nav-link text-capitalize active">Audit Category
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/branch/audit_question_list') }}" type="button" class="nav-link text-capitalize ">Audit Category Questions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/settings/branch/audit_score') }}" type="button"
                        class="nav-link text-capitalize ">Audit score</a>
                </li>
            </ul>
        </div>

        <div class="card">

            <div class="card-body">
                <div class="d-flex justify-content-end align-items-center mb-2">
                    <a href="javascript:;" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_audit_category">
                        <span class="me-2"><i class="mdi mdi-plus"></i></span>Add Audit Category
                    </a>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table align-middle table-row-dashed table-striped table-hover gy-0 gs-1 list_page">
                            <thead>
                                <tr class="text-start align-top fw-bold fs-6 gs-0 bg-primary">
                                    <th class="min-w-300px">Audit Category</th>
                                    <th class="min-w-80px">Status</th>
                                    <th class="min-w-50px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-black fw-semibold fs-7">
                                @if (isset($Department))
                                @foreach ($Department as $category)
                                <tr>
                                    <td>
                                        <label>{{ $category->audit_category_name }}</label>
                                        <a href="javascipt:;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $category->audit_category_desc ?? '-' }}"><i class="mdi mdi mdi-help-circle text-dark"></i></a>
                                    </td>
                                    <td>
                                        <label class="switch switch-square">
                                            <input type="checkbox" class="switch-input" {{ $category->status == 0 ? 'checked' : '' }} onchange="updateDepartmentStatus('{{ $category->sno }}', this.checked)" />
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on"></span>
                                                <span class="switch-off"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="text-end">
                                            <a href="#" onclick="openEditModal('{{ $category->sno }}',
                                                        '{{ $category->audit_category_name }}',
                                                        '{{ $category->audit_category_desc }}')" class="btn btn-icon btn-sm me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_audit_category" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                <i class="mdi mdi-square-edit-outline fs-3 text-black"></i>
                                            </a>
                                            <a href="#" onclick="confirmDelete('{{ $category->sno }}',
                                                '{{ $category->audit_category_name }}',
                                                '{{ $category->audit_category_id }}')" class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_delete_audit_category" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                                <i class="mdi mdi-delete-outline fs-3 text-black"></i>
                                            </a>
                                            <a href="#" class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_view_audit" onClick='viewAuditQuestion({{ $category->sno }},"{{$category->audit_category_name}}")'>
                                                <span><i class="mdi mdi-eye-outline fs-3 text-black me-1"></i></span>
                                                
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!--begin::Modal - View review audit-->
    <div class="modal fade" id="kt_modal_view_audit" tabindex="-1" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" data-bs-focus="false">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-xl">
            <!--begin::Modal content-->
            <div class="modal-content rounded">
                <!--begin::Modal header-->
                <div class="modal-header justify-content-end border-0 pb-0">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body pt-0 pb-10 px-10 px-xl-20">
                    <!--begin::Heading-->
                    <div class="mb-6">
                        <h3 class="text-center text-black">Audit Questions
                            <!-- <label class="me-4 badge bg-warning text-black rounded fw-bold fs-4">Email</label> -->
                        </h3>
                    </div>
                    <div class="row">
                        <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <label class="col-4 text-dark fs-7 fw-semibold">Audit Category</label>
                                <label class="col-1 text-black fs-6 fw-bold">:</label>
                                <label class="col-7 text-black fs-7 fw-semibold" ><span id="view_category_name"></label>
                            </div>
                        </div>
                        </div>
                        <div class="" id="question_table">
                            
                        </div>
                    </div>
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - View review audit-->

<!--begin::Modal - Add department-->
<div class="modal fade" id="kt_modal_add_audit_category" tabindex="-1" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-md">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header justify-content-end border-0 pb-0">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body pt-0 pb-10 px-10 px-xl-20">
                <!--begin::Heading-->
                <div class="mb-4 text-center">
                    <h3 class="text-center mb-4 text-black">Create Audit Category</h3>
                </div>
                <form method="POST" action="{{ route('add_audit_category') }}" onsubmit="return AddvalidateForm_department()">
                    @csrf
                    <div class="row">
                        <!-- Basic -->
                        <div class="col-lg-12 mb-3">
                            <label class="text-dark mb-1 fs-6 fw-semibold">Audit Category Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="audit_category_name_add" name="audit_category_name" placeholder="Enter Category" />
                            <div class="text-danger" id="audit_category_name_err"></div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="text-dark mb-1 fs-6 fw-semibold">Description</label>
                            <textarea class="form-control" rows="1" name="audit_category_desc" placeholder="Enter Description"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <button type="reset" class="btn btn-secondary me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Audit Category</button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add department-->
<!--begin::Modal - Edit department-->
<div class="modal fade" id="kt_modal_edit_audit_category" tabindex="-1" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-md">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header justify-content-end border-0 pb-0">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body pt-0 pb-10 px-10 px-xl-20">
                <!--begin::Heading-->
                <div class="mb-4 text-center">
                    <h3 class="text-center mb-4 text-black">Update Audit Category</h3>
                </div>
                <form id="validationFormedit" class="needs-validation" method="POST" action="{{ route('audit_category_update') }}" onsubmit="return EditvalidateForm_department()">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="text-dark mb-1 fs-6 fw-semibold">Audit Category Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_department_Name" name="audit_category_name" placeholder="Enter Audit Category" />
                            <input type="hidden" class="form-control" name="audit_category_id" id="edit_department_Id"  />
                            <div class="text-danger" id="edit_department_Name_err"></div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="text-dark mb-1 fs-6 fw-semibold">Description</label>
                            <textarea class="form-control" rows="1" id="edit_department_Desc" name="audit_category_desc" placeholder="Enter Description"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <button type="reset" id="cancelButton" class="btn btn-secondary me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Audit Category</button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Edit department-->
<!--begin::Modal - Delete department-->
<div class="modal fade" id="kt_modal_delete_audit_category" tabindex="-1" aria-hidden="true" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-m">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <div class="swal2-icon swal2-danger swal2-icon-show" style="display: flex;">
                <div class="swal2-icon-content">?</div>
            </div>
            <div class="swal2-html-container" id="swal2-html-container" style="display: block;"><span id="delete_message"></span></div>
            <div class="d-flex justify-content-center align-items-center pt-8">
                <button type="submit" class="btn btn-danger me-3" data-bs-dismiss="modal" onclick="deleteDepartmentCategory()">Yes, delete!</button>
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">No,cancel</button>
            </div><br><br>
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Delete department-->


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<style>
    /* Customize Toastr container */
    .toast {
        background-color: #39484f;
    }

    /* Customize Toastr notification */
    .toast-success {
        background-color: green;
    }

    /* Customize Toastr notification */
    .toast-error {
        background-color: red;
    }

    .error_msg {
        border: solid 2px red !important;
        border-color: red !important;
    }

</style>
<script>
    // Display Toastr messages
    @if(Session::has('toastr'))
    var type = "{{ Session::get('toastr')['type'] }}";
    var message = "{{ Session::get('toastr')['message'] }}";
    toastr[type](message);
    @endif

</script>
<script>
    function AddvalidateForm_department() {
        var err = 0;

        // Validate department Name
        var audit_category_name_add = document.getElementById("audit_category_name_add").value.trim();
        if (audit_category_name_add === "") {
            document.getElementById('audit_category_name_err').innerHTML = 'Enter Audit Category is required...!';
            document.getElementById('audit_category_name_add').classList.add('error_msg');
            err++;
        } else {
            document.getElementById('audit_category_name_add').classList.remove('error_msg');
            document.getElementById('audit_category_name_err').innerHTML = '';
        }

        return err === 0; // Returns true if there are no errors
    }

</script>
<script>
    function EditvalidateForm_department() {
        var err = 0;

        // Validate branchtype Name
        var edit_department_Name = document.getElementById("edit_department_Name").value.trim();
        if (edit_department_Name === "") {
            document.getElementById('edit_department_Name_err').innerHTML = 'Enter Audit Category is required..!';
            document.getElementById('edit_department_Name').classList.add('error_msg');
            err++;
        } else {
            document.getElementById('edit_department_Name').classList.remove('error_msg');
            document.getElementById('edit_department_Name_err').innerHTML = '';
        }

        return err === 0; // Returns true if there are no errors
    }

</script>

<script>
    function openEditModal(sno, department_Name, department_Desc) {

        document.getElementById('edit_department_Id').value = sno;
        document.getElementById('edit_department_Name').value = department_Name;
        document.getElementById('edit_department_Desc').value = department_Desc;
    }

</script>
<script>
    function confirmDelete(id, name, ids) {
        document.querySelector('#kt_modal_delete_audit_category .btn-danger').setAttribute(
            'data-id', id);
        $('#delete_message').html('Are you sure you want to delete <br> <b class="text-danger"> ' + name +
            '</b> Audit Category ?');
    }

    function deleteDepartmentCategory() {

        var categoryId = document.querySelector('#kt_modal_delete_audit_category .btn-danger').getAttribute('data-id');

        fetch('/audit_category_delete/' + categoryId, {
                method: 'DELETE'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    toastr.success('Audit Category Deleted successfully!');
                    location.reload();

                } else {

                    console.error(data.error_msg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateDepartmentStatus(categoryId, isChecked) {
        const status = isChecked ? 0 : 1; // Set status based on checkbox state

        fetch(`/audit_category_status/${categoryId}`, {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                }
                , body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    toastr.success('Audit Category status Updated successfully!');
                } else {
                    toastr.error('Error updating Audit Category status');
                }
            })
            .catch(error => {
                console.error('Error updating Audit Category status:', error);
            });
    }
    $(".list_page").DataTable({
        "ordering": false,
        // "aaSorting":[],
        "language": {
            "lengthMenu": "Show _MENU_"
        , }
        , "dom": "<'row mb-3'" +
            "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
            "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
            ">" +

            "<'table-responsive'tr>" +

            "<'row'" +
            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
            ">"
    });

</script>

<script>
     function viewAuditQuestion(dept_id) {
        $.ajax({
            url: '/audit_category_questions/' + dept_id, // URL to your route
            type: 'GET',
            success: function(response) {
                if(response.success) {
                      $("#view_category_name").text(response.data.audit_category_name);
                   
                    
                        // Handle dynamic departments
                        const questions = response.data.questions; // Expected format: Array of question objects for each department

                        // Dynamically generate the tabs
                        let tabHtml = '';
                        let tabContentHtml = '';
                       
                         
                            
                            tabContentHtml += `
                                                    <table class="table align-middle table-row-dashed table-striped table-hover  gy-1 gs-2 std_list_page table-scrollable" id="studs_table">
                                                        <thead>
                                                            <tr class="text-start align-top fw-bold fs-7 gs-0 bg-primary">
                                                                <th class="min-w-50px">Sno</th>
                                                                <th class="min-w-150px">Questions</th>
                                                                <th class="min-w-150px">Type</th>
                                                                <th class="min-w-150px">Answers</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold fs-7  ">`;

                            // Generate rows for each department's questions
                            let qust_sno = 1;
                            let score = 0;
                            let total_score = 0;
                            questions.forEach((question) => {
                                
                                    tabContentHtml += `<tr>
                                                            <td>
                                                                <div class="text-black fw-semibold fs-7">${qust_sno}</div>
                                                            </td>
                                                            <td>
                                                                <div class="text-black fw-semibold fs-7">${question.question_name || '-'}</div>
                                                            </td>
                                                            <td>
                                                                <div class="text-black fw-semibold fs-7">${question.audit_question_type || '-'}</div>
                                                            </td>
                                                            <td class="text-center border">
                                                                <div class="text-black fw-semibold fs-7">${question.options || '-'}</div>
                                                            </td>
                                                        </tr>`;
                                                        qust_sno++;       
                                
                            });

                            tabContentHtml += ` </tbody>
                                                </table>
                                           `; // Close the tab content
                       

                        // Insert the dynamic content into the modal
                       
                        $("#question_table").html(tabContentHtml);
                    
                    
                } else {
                    console.log('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error fetching Audit data:', error);
            }
        });
    }
</script>

@endsection
