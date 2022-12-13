<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/index.js') }}"></script>
<script src="{{ asset('js/vendor/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('js/vendor/smooth-scrollbar.min.js') }}"></script>
@auth
    <script src="{{asset("js/vendor/tables/datatable/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/dataTables.bootstrap5.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/dataTables.responsive.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/responsive.bootstrap5.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/datatables.buttons.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/jszip.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/pdfmake.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/vfs_fonts.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/buttons.html5.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/buttons.print.min.js")}}"></script>
    <script src="{{asset("js/vendor/tables/datatable/dataTables.rowGroup.min.js")}}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/cleave.min.js') }}"></script>
    <script src="{{asset("js/vendor/cleave-phone.us.js")}}"></script>
    <script src="{{ asset('js/vendor/main.js') }}"></script>
    <script src="{{asset('js/jquery.maskMoney.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
    <script>
        function startLoading() {
            $.blockUI({
                message: '<div class="spinner-border text-white" role="status"></div>',
                css: {backgroundColor: 'transparent', border: '0'},
                overlayCSS: {opacity: 0.5}
            });
        }

        function stopLoading() {
            $.unblockUI();
        }

        window.stopLoading = stopLoading;
        window.startLoading = startLoading;

        $(function () {
            'use strict';
            var bootstrapForm = $('.needs-validation');

            if (bootstrapForm.length) {
                Array.prototype.filter.call(bootstrapForm, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            form.classList.add('invalid');
                            event.preventDefault();
                        }
                        form.classList.add('was-validated');
                        startLoading();
                        $(this).submit();
                    });
                    bootstrapForm.find('input, textarea').on('focusout', function () {
                        $(this).removeClass('is-valid is-invalid')
                            .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
                    });
                });
            }

            $('.money').maskMoney();

            @if (!isset($nodatatable))
            $('.table').dataTable();
            @endif



            flatpickr('.datepicker');
        })

    </script>
@endauth
