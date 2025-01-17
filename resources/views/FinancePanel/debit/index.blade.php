@extends('FinancePanel.main')

@section('content-finance')

<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('css/panel_computer_log.css') }}">

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-8">

                    <h1>Procesar <small>pagos</small></h1>

                </div>

                <div class="col-sm-4">

                    <ol class="breadcrumb float-sm-right">

                      <button data-toggle="modal" data-target="#modalDebit" class="btn btn-primary" data-placement="top" title="Generar nuevo">
                        <i  class="fa fa-plus"></i>
                      </button>

                      <button data-toggle="modal" data-target="#modalPrintTickets" class="btn btn-info" style="margin-left: 5px" data-placement="top"  title="Imprimir recibos">
                        <i class="fa fa-print"></i>
                      </button>

                      <button data-toggle="modal" data-target="#modal-generate-excel" class="btn btn-success" style="margin-left: 5px" data-placement="top"  title="Exportar excel">
                        <i class="fa fa-file-excel"></i>
                      </button>

                    </ol>

                </div>

            </div>

        </div>

    </section>

  <section class="content">

      <div class="card">

          <div class="card-body">

              <div class="row">

                  <div class="col-md-12">
                    <h4>Filtros</h4>
                  </div>

                  <div class="col-md-4">

                      <label for="">Estatus</label>

                      <div class="input-group mb-3">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="fas fa-toggle-on"></i></span>
                        </div>

                        <select id="mode" class="form-control">
                          @php
                          $array = [
                            ["value" => "0", "text" => "Pendientes", "selected" => false],
                            ["value" => "1", "text" => "Validado", "selected" => false],
                            ["value" => "3", "text" => "Pagado", "selected" => false]
                          ];
                          @endphp

                          @foreach($array as $key => $value)
                              <option value="{{$value['value']}}">{{$value['text']}}</option>
                          @endforeach
                        </select>

                      </div>

                  </div>

                  <div class="col-md-4">
                    <label for="">Periodo</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                          <i class="fas fa-th-list"></i></span>
                      </div>

                      <select id="period" class="form-control">
                        @foreach(periodsById() as $key => $value)
                           <option value="{{$value->id}}">{{$value->clave}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">

                    <label for="">Concepto</label>

                    <div class="input-group mb-3">

                      <div class="input-group-prepend">
                          <span class="input-group-text">
                          <i class="fas fa-asterisk"></i></span>
                      </div>

                      <select id="concept" class="form-control">
                        <option value="all">Todos</option>
                        @foreach(selectTable('debit_type') as $key => $value)
                           <option value="{{$value->id}}">{{$value->concept}}</option>
                        @endforeach
                      </select>

                    </div>

                  </div>

                  <div class="col-md-4">
                    <label for="">Método de pago</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                          <i class="fas fa-credit-card"></i></span>
                      </div>

                      <select id="payment_method" class="form-control">
                        <option value="all">Todos</option>
                        <option value="transfer">Transferencia</option>
                        <option value="oxxo_cash">OXXO</option>
                        <option value="spei">SPEI</option>
                        <option value="card">Pago con tarjeta</option>
                      </select>
                    </div>
                  </div>

              </div>

          </div>

      </div>

      <div class="card">

          <div class="card-body">

              <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

              <table class="table table-bordered table-hover dt-responsive tableDebits">

                  <thead>

                      <tr>

                          <th>Matricula</th>
                          <th>Acciones</th>
                          <th>Alumno</th>
                          <th>Descripción</th>
                          <th>Metodo pago</th>
                          <th>Importe</th>
                          <th>Status</th>
                          <th>F. pago</th>
                          <th>Fecha</th>
                          <th>Carrera</th>
                          <th>Télefono</th>
                          <th>Correo</th>
                          <th>Localidad</th>
                          <th>Estado</th>
                       
                      </tr>

                  </thead>

              </table>

          </div>

      </div>

  </section>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

 <!-- Modal crear nuevo adeudo -->

<div class="modal fade" id="modalDebit">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

        <form method="post" action="{{route('finance.debit.save')}}">
            
            {{ csrf_field() }}

            <div class="modal-header">

                <h4 class="modal-title">GENERAR NUEVO ADEUDO</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        
            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <select name="debit_type_id" id="debit_type_id" class="form-control">
                              <option value="" disabled selected>Seleccione un concepto</option>
                              @foreach(getDebitType() as $key => $value)
                              <option value="{{$value->id}}">{{$value->concept}}</option>
                              @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i></span>
                            </div>

                            <input type="number" step="any" min="0" name="amount" placeholder="¿Cual es el monto?" class="form-control" required>

                        </div>

                    </div>

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <select class="form-control" name="id_alumno" id="id_alumno" style="width:100%" require>
                                <option value="">Seleccione un alumno</option>
                            </select>

                        </div>

                    </div>

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-ad"></i></span>
                            </div>

                            <textarea type="text" name="description" placeholder="Ingrese una descripción" class="form-control" required></textarea>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer justify-content">

                <div class="col-sm container-fluid">

                    <div class="row">

                        <div class=" col-sm-6 btn-group">

                        <button id="cancel" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        </div>

                        <div class=" col-sm-6 btn-group">

                        <button type="submit" id="sale" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                        
                        </div>

                    </div>

                </div>

            </div>

       </form>

    </div>

  </div>

</div>

 <!-- Modal Editar-->
<div class="modal fade" id="modalEdit">

  <div class="modal-dialog">

    <div class="modal-content">

      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenUpdate">

      <div class="modal-header">

          <h4 class="modal-title">EDITAR ADEUDO</h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>

      </div>

      <form method="post" action="{{route('finance.debit.update')}}">
            
        {{ csrf_field() }}

        <input type="hidden" id="debitId" name="debitId">           
        
        <div class="modal-body">

          <div class="row">

            <div class="col-md-12">           

                <label for="">Monto</label>

                <div class="input-group mb-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="fas fa-dollar-sign"></i></span>
                    </div>

                    <input type="number" step="any" min="0" name="amount" id="amount" placeholder="Monto" class="form-control" required>

                </div>

            </div>

            <div class="col-md-12">  

              <div class="row">

                <div class="col-md-12">

                  <input type="hidden" name="id_alumno" id="hidden_id_alumno">

                  <p>Alumno: <span id="alumnName"></span></p>

                </div>

              </div>

              <button type="button" class="btn btn-success" id="showSelectAlumno" style="margin: 1rem 0rem 1rem 0rem">Cambiar alumno</button>

              <div class="selectAlumno" style="display: none">

                <label for="">Alumno</label>      

                <div class="input-group mb-3">

                  <div class="input-group-prepend">
                      <span class="input-group-text">
                      <i class="fas fa-user"></i></span>
                  </div>               

                  <select class="form-control" id="select_alumno_id" style="width:90%">
                    <option value="">Seleccione un alumno</option>
                  </select>

                </div>
              </div>

            </div>

            <div class="col-md-12">  

                <label for="">Descripción</label>

                <div class="input-group mb-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="fas fa-ad"></i></span>
                    </div>

                    <textarea type="text" name="description" id="description" placeholder="Ingrese una descripción" class="form-control" required></textarea>

                </div>

            </div>

            <div class="col-md-6">
              <div id="edit-container" style="display: none">
              <label>Verifica el comprobante aqui</label>
                <button type="button" class="btn btn-info showPdf" id="edit-button" style="width: 100%;"><i class="fa fa-file"></i> Ver comprobante</button>
              </div>
            </div>

            <div class="col-md-5">           

              <label for="">Estatus</label>

              <div class="input-group mb-3">

                <select name="status" class="form-control" id="editar_status_adeudo">
                  <option value="0">Pendiente</option>
                  <option value="1">Validado</option>
                  <option value="3">Pagado</option>
                </select>

              </div>

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <div class="row" style="width: 100%;">

              <div class=" col-md-6">

                <button id="closeDetails" type="button" class="btn btn-danger .px-2" 
              data-dismiss="modal" style="width: 100%"><i class="fa fa-times"></i> Cerrar</button>

              </div>

              <div class=" col-md-6">

                <button class="btn btn-success .px-2" style="width: 100%"><i class="fa fa-check" ></i> Guardar</button>

              </div>

          </div>

        </div>

      </form>

    </div>

  </div>

</div>

<!-- TerminaModal -->

<!-- Modal  de detalles de pago -->
<div class="modal fade" id="modalShowDetails" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-lg">

    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">            
            
            {{ csrf_field() }}
            <input type="hidden" id="DebitIdUpdate" name="DebitId">
           

            <div class="modal-header">

                <h4 class="modal-title">DETALLES DEL PAGO</h4>

                

            </div>
        
            <div class="modal-body custom-modal">

              <div id="loader" class="loader"></div>
              <h6 id="detail-id"></h6>
              <h6 id="detail-paymentMethod"></h6>
              <h6 id="detail-reference"></h6>
              <h6 id="detail-amount"></h6>
             

            </div>

            <div class="modal-footer justify-content">

                <div class="col-sm container-fluid">

                    <div class="row" style="margin-left: 32%">

                        <div class=" col-sm-6 btn-group">

                        <button id="closeDetails" type="button" class="btn btn-danger .px-2 " 
                        data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>

                        </div>

                    </div>

                </div>

            </div>

       

    </div>

  </div>

</div>

<div class="modal fade" id="modalInscripcion" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="loader-modal">

        <div class="loader-spinner">Loading...</div>

      </div>

      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenValidate"> 
      
      <div class="modal-header">

          <h4 class="modal-title">Validar pago de inscripción</h4>               

      </div>

      <form action="{{ route('finance.debit.validate') }}" method="post">

        {{ csrf_field() }}

        <input type="hidden" value="" name="debit_id" id="validate_debit_id">

        <div class="modal-body custom-modal">

          <div class="row" id="step1">

            <div class="col-md-12" style="display: none">
              <p>El alumno <span id="alumn_name_step_1"></span> no ha subido comprobante o realizado un pago</p>
            </div>

          </div>

          <div class="row" id="step2" style="display: none">

            <div class="col-md-12" id="step-validation" style="display: none">

              <p>Antes de validar el pago, asegurece de validar el id en CONEKTA o el link con el comprobante</p>

              <div class="receipt">
                
              </div>

              <p>Una vez verificado, puede validar el adeudo, o bien, marcar como pagado el adedudo en la lista a continuacion.</p>

              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <select name="verificacion_adedudo" class="form-control" id="verificacion_adedudo">
                        <option value="0">Pendiente</option>
                        <option value="1">Validado</option>
                        <option value="3">Pagado</option>
                      </select>
                    </div>
                  </div>
                </div>
  
              </div>

            </div>

            <div class="col-md-12" id="step-finally" style="display: none">

              <div class="receipt">
                
              </div>

              <p>Este adedudo ya ha sido validado, esto quiere decir, que el proceso de validación de la inscrición ya fue realizado y todos los pagos fueron saldados</p>

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <div class="row" style="width: 100%;">

              <div class=" col-md-6">

                <button id="closeDetails" type="button" class="btn btn-danger .px-2" 
              data-dismiss="modal" style="width: 100%"><i class="fa fa-times"></i> Cerrar</button>

              </div>

              <div class=" col-md-6">

                <button id="validate-button" class="btn btn-success .px-2" style="width: 100%"><i class="fa fa-check" ></i> Guardar</button>

              </div>

          </div>

        </div>

      </form>       

    </div>

  </div>
  
</div> 

<div class="modal fade" id="modalUpload">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Subir un comprobante</h3>

      </div>
        
        <div class="modal-body">

          <form action="{{route('finance.debit.upload')}}" method="post" enctype="multipart/form-data">
              
            {{ csrf_field() }}

            <div class="row">

              <div class="col-md-12">
                
                <div class="form-group">

                <div class="panel">SUBIR COMPROBANTE</div>
                  <input type="hidden" name="debit_id" id="debit_id_upload">
                  <input type="file" name="file" id="ticket" required>

                </div>

              </div>

            </div>

            <div class="row">

              <div class="col-md-12">

                <div class="form-group" id="pay-now" style="margin-top: 10vh;">

                  <button class="btn btn-danger" type="button" data-dismiss="modal">Cerrar</button>
                  <button class="btn btn-success" type="submit" style="float: right;">subir</button>
                  
                </div>

              </div>

            </div>
              
          </form>

        </div>

    </div>

  </div>

</div>

<div class="modal fade" id="modalPrintTickets">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h4>Imprimir comprobantes</h4>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

      <form action="{{route('finance.ticket.report')}}" method="post">

        {{ csrf_field() }}

        <div class="modal-body">

          <div class="row">

            <div class="col-md-12">

              <label for="">Rango de fechas</label>

              <div class="input-group mb-3">

                <div class="input-group-prepend">

                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>

                </div>

                <input type="text" class="form-control" id="datepicker-report" name="dates" placeholder="Rango de fechas">

              </div>

            </div>

            <div class="col-md-12">           

              <label for="">Concepto</label>

              <div class="input-group mb-3">

                <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="fas fa-asterisk"></i></span>
                </div>

                <select name="debit_type_id" id="debit_type_id" class="form-control">

                  <option value="" disabled selected>Todos</option>

                  @foreach(getDebitType() as $key => $value)
                  <option value="{{$value->id}}">{{$value->concept}}</option>
                  @endforeach

                </select>

              </div>

            </div>

          </div>         

        </div>

        <div class="modal-footer">
                            
          <button class="btn btn-success" type="submit">Imprimir</button>

        </div>

      </form>

    </div>

  </div>

</div>

<div class="modal fade" id="modal-generate-excel">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h4>Generar excel de adeudos</h4>

      </div>
        
      <div class="modal-body">
           
        <div class="row">

          <div class="col-md-12">           

            <label for="">Periodo</label>

            <div class="input-group mb-3">

                <select name="period_id" id="period_id" class="form-control">
                  <option value="" selected>Todos</option>
                  @foreach(periodsById() as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->clave }}</option>
                  @endforeach
                </select>

                <input type="hidden" value="{{ csrf_token() }}" id="token-excel">

            </div>

          </div>

          <div class="col-md-12">           

            <label for="">Estatus</label>

            <div class="input-group mb-3">

                <select name="is_paid" id="is_paid" class="form-control">
                  <option value="" selected>Todos</option>
                  <option value="0">Pendientes</option>
                  <option value="1">Pagados</option>
                </select>

            </div>

          </div>

          <div class="col-md-12">

            <label for="">Rango de fechas</label>

            <div class="input-group mb-3">

              <div class="input-group-prepend">

                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>

              </div>

              <input type="text" class="form-control datepicker-report" id="excel-date-range" placeholder="Rango fechas">

            </div>

          </div>

        </div>
            
      </div>

      <div class="modal-footer">
        
        <div class="row" style="width: 100%;">

          <div class="col-md-6">

              <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
              
          </div>

          <div class="col-md-6">

            <button class="btn btn-success" id="generate-excel" style="float: right;">Generar</button>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script lang="javascript" src="{{ asset('excel/sheetjs/dist/xlsx.full.min.js')}}"></script>
<script lang="javascript" src="{{ asset('excel/FileSaver/dist/FileSaver.min.js')}}"></script>
<script src="{{asset('js/financePanel/debit.js')}}"></script>


<script>
  $('#datepicker-report').daterangepicker(configDate, function(start, end, label) {
    $("#datepicker-report").val(start.format('YYYY-MM-DD') + '|' + end.format('YYYY-MM-DD'));
  });

  $('#excel-date-range').daterangepicker(configDate, function(start, end, label) {
    $("#excel-date-range").val(start.format('YYYY-MM-DD') + '|' + end.format('YYYY-MM-DD'));
  });
</script>
@stop