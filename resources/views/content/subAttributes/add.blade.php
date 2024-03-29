@extends('layouts/contentLayoutMaster')

@section('title', 'Özellik Alt Grubu Ekle')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
@endsection

@section('content')
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-body">
                <form>
                    <div class="mb-1">
                        <x-inputs.text-input name="product_name" label="Sıra no" placeholder="" />
                    </div>
                    <div class="mb-1">
                        <x-inputs.text-input name="product_name" label="Özellik Adı" placeholder="" />
                    </div>

                    <div class="form-check mt-1">
                        <input name="default-radio-1" class="form-check-input" type="radio" value=""
                            id="choose" checked />
                        <label class="form-check-label" for="choose">
                            Seçenek Listesi (Tek Seçim)
                        </label>
                    </div>
                    <div class="form-check mt-1">
                        <input name="default-radio-1" class="form-check-input" type="radio" value=""
                            id="opener" />
                        <label class="form-check-label" for="opener">
                            Aşağı Açılır Liste (Tek Seçim)
                        </label>
                    </div>
                    <div class="form-check mt-1">
                        <input name="default-radio-1" class="form-check-input" type="radio" value=""
                            id="multiple" />
                        <label class="form-check-label" for="multiple">
                            Çoklu Seçim
                        </label>
                    </div>

                    <x-button type="submit" buttonType="primary mt-3" value="Submit" name="submit" label="Kaydet" />
                </form>
            </div>
        </div>
    </div>
@endsection



@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-quill-editor.js')) }}"></script>
    <script>
        var flatpickrRange = document.querySelector("#flatpickr-range");

        flatpickrRange.flatpickr({
            mode: "range"
        });
    </script>
@endsection
