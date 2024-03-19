@extends('afiliados.layout.menuAfiliados')

@section('title-page')
    Rifas Disponíveis
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/datatables.min.css') }}" />
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <label class="badge bg-success">&nbsp;</label> <span>Afiliado</span> <br>
            <label class="badge bg-secondary">&nbsp;</label> <span>Disponível</span>
        </div>
    </div>

    <div class="data-table-rows slim">
        <div class="row">
            <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                    <input class="form-control datatable-search" placeholder="Buscar" data-datatable="#datatableRows" />
                    <span class="search-magnifier-icon">
                        <i data-acorn-icon="search"></i>
                    </span>
                    <span class="search-delete-icon d-none">
                        <i data-acorn-icon="close"></i>
                    </span>
                </div>
            </div>

            <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">
                <div class="d-inline-block">
                    <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableRows"
                        data-childSelector="span">
                        <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" data-bs-offset="0,3">
                            <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-delay="0" title="Item Count">
                                10 Items
                            </span>
                        </button>
                        <div class="dropdown-menu shadow dropdown-menu-end">
                            <a class="dropdown-item" href="#">5 Items</a>
                            <a class="dropdown-item active" href="#">10 Items</a>
                            <a class="dropdown-item" href="#">20 Items</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="data-table-responsive-wrapper text-center">
            <table id="datatableRows" class="data-table nowrap hover">
                <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">#</th>
                        <th class="text-muted text-small text-uppercase">MINIATURA</th>
                        <th class="text-muted text-small text-uppercase">RIFA</th>
                        <th class="text-muted text-small text-uppercase">SORTEIO</th>
                        <th class="text-muted text-small text-uppercase">VALOR DA COTA</th>
                        <th class="text-muted text-small text-uppercase">% AFILIADO</th>
                        <th class="text-muted text-small text-uppercase">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rifas as $rifa)
                        <tr>
                            <td>{{ $rifa->id }}</td>
                            <td style="width: 50px;" class="text-center">
                                <img style="border-radius: 5px;"
                                    src="/products/{{ $rifa->imagem() ? $rifa->imagem()->name : '' }}" width="50"
                                    alt="">
                                @if ($rifa->checkAfiliado)
                                    <label class="badge bg-success">&nbsp;</label>
                                @else
                                    <label class="badge bg-secondary">&nbsp;</label>
                                @endif
                            </td>
                            <td>{{ $rifa->name }}</td>
                            <td>{{ $rifa->status }}</td>
                            <td>{{ $rifa->price }}</td>
                            <td>{{ $rifa->ganho_afiliado . '%' }}</td>
                            <td>
                                <a class="btn btn-sm btn-primary {{ $rifa->checkAfiliado ? 'disabled btn-outline-primary' : '' }}"
                                    href="{{ route('afiliado.afiliarSe', $rifa->id) }}">Afiliar-se</a>
                                <button class="btn btn-sm btn-info {{ !$rifa->checkAfiliado ? 'disabled btn-outline-info' : '' }}" data-url="{{ route('product', $rifa->slug) }}"
                                    data-token="{{ $rifa->getAfiliadoToken }}"
                                    onclick="getLinkAfiliado(this)">Link</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Table End -->
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/dataTable/rifasAtivas.js') }}"></script>
    <script src="{{ asset('js/forms/controls.datepicker.js') }}"></script>
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.validate/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('js/vendor/jquery.validate/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/forms/validation.js') }}"></script>

    <script>
        function getLinkAfiliado(el) {
            var url = el.dataset.url;
            var token = el.dataset.token;
            var link = `${url}/${token}`;

            $('#link-afiliado').val(link);
            $('#link-facebook').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${link}`);
            $('#link-telegram').attr('href', `https://telegram.me/share/url?url=${link}`)
            $('#link-wpp').attr('href', `https://api.whatsapp.com/send?text=${link}`)
            $('#link-twitter').attr('href', `https://twitter.com/intent/tweet?text=Vc%20pode%20ser%20o%20Próximo%20Ganhador%20${link}`)
            $('#modal-link').modal('show');
        }

        function copiarLink() {
            var copyText = document.getElementById("link-afiliado");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            alert("Link copiado com sucesso.");

        }

        function closeModal() {
            $('#modal-link').modal('hide')
        }
    </script>
@endsection



@section('modal')
    <div class="modal fade" id="modal-link" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Link de Afiliado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 text-center">
                        <p> Divulge o link abaixo e ganhe a cada compra com o seu link!</p>
                    </div>

                    <div class="col-md-12 mt-4">
                        <input type="text" id="link-afiliado" class="form-control text-center">
                    </div>
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-sm btn-info" style="color: #fff;" onclick="copiarLink()">Copiar</button>
                            <a class="btn btn-primary" id="link-facebook"
                                style="background-color: #2760AE;border: none;font-size: 20px;" href=""
                                target="_blank" rel="noreferrer noopener" role="button"><i class="bi bi-facebook"></i></a>
                            <!-- Telegram -->
                            <a class="btn btn-primary" id="link-telegram" style="background-color: #2F9DDF;border: none;"
                                href="" target="_blank" rel="noreferrer noopener" role="button"><i
                                    class="bi bi-telegram" style="font-size: 20px;"></i></a>
                            <!-- Whatsapp -->
                            <a class="btn btn-primary" id="link-wpp" style="background-color: #25d366;border: none;"
                                href="" target="_blank" rel="noreferrer noopener" role="button"><i
                                    class="bi bi-whatsapp" style="font-size: 20px;"></i></a>
                            <!-- Twitter -->
                            <a class="btn btn-primary" id="link-twitter" style="background-color: #34B3F7;border: none;"
                                href="" target="_blank" rel="noreferrer noopener" role="button"><i
                                    class="bi bi-twitter" style="font-size: 20px;"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
