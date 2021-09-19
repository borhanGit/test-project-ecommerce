@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Order List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Order">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            NAME
                        </th>
                        <th>
                            ADDRESS
                        </th>
                        <th>
                            PHONE
                        </th>
                        
                        <th>
                            QUANTITY
                        </th>
                        <th>
                            PRICE
                        </th>
                        <th>
                            TRANSACTION ID
                        </th>
                        <th>
                            PAYMENT METHOD
                        </th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                        <tr data-entry-id="{{ $order->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $order->id ?? '' }}
                            </td>
                            <td>
                                {{ $order->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $order->user->address ?? '' }}
                            </td>
                            <td>
                                {{ $order->user->phone ?? '' }}
                            </td>
                            <td>
                                {{ $order->quantity ?? '' }}
                            </td>
                            
                            <td>
                                {{ $order->amount ?? '' }}
                            </td>
                           
                            <td>
                                {{ $order->transaction_id ?? '' }}
                            </td>
                            <td>
                                {{ $order->payment_method==1 ? 'CASH' : 'ONLINE' ?? '' }}
                            </td>
                           

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('user_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.users.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Order:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection