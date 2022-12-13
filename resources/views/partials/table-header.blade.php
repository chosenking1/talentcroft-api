<form method="GET" id="per-page-search">
    <div class="row mb-2">
        <style>
            .perPage {
                width: 65px;
            }

            .search-form {
                flex-direction: column;
                width: 240px;
            }

            @media screen and (max-width: 767px) {
                .perPage {
                    width: 100%;
                }
                .search-form{
                    width: 100%;
                }
            }
        </style>
        <div class="col-sm-12 col-md-6">
            <div class="fs-12 font-italic font-weight-lighter">
                Show Entries
            </div>
            <select
                onchange="document.getElementById('per-page-search').submit()"
                name="perPage" class="form-select perPage form-select-sm">
                @foreach([10, 25,50, 100] as $c)
                    <option @if(request()->get('perPage', "10") === "$c")selected @endif>{{$c}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-12 col-md-6 mt-3 mt-md-0">
            <div class="ms-auto search-form me-1">
                <div class="fs-12 font-italic font-weight-lighter">
                    {{$message??''}}
                </div>
                <input type="search" onchange="onchangeInput(this)"
                       name="search" class="form-control ms-1 form-control-sm   "
                       value="{{request()->get('search', "")}}">
                <script>
                    function onchangeInput(target) {
                        let value = target.value
                        let form = document.getElementById('per-page-search');
                        form.submit()
                    }
                </script>
            </div>
        </div>
    </div>

</form>
