<div class="card card-xxl-stretch">
    <div class="card-body py-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-bordered table-row-dashed" id="kt_table_widget_1">
                <tbody>
                    <tr class="text-start text-gray-400 fw-boldest text-uppercase text-primary">
                        @foreach ($headers as $key => $value)
                            @if($value == "Action")
                                <th width="280px">{{ $value }}</th>
                            @else
                                <th class="min-w-125px">{{ $value }}</th>
                            @endif
                        @endforeach
                    </tr>
                    @foreach ($body as $row)
                        <tr>
                            @foreach ($row as $key => $value)
                                @switch($key)
                                    @case('action')
                                        <?php 
                                            $show_route = $routename .'.show'; 
                                            $edit_route = $routename .'.edit'; 
                                            $delete_route = $routename .'.destroy'; 
                                        ?>
                                        <td>
                                            <!-- <a href="{{ route($show_route,$value) }}"><i class="fa-solid fa-pen-to-square"></i> Show</a> -->
                                            <a class="px-3" href="{{ route($edit_route,$value) }}"><i class="fa-regular fa-eye"></i> View</a>
                                                
                                            <!-- {!! Form::open(['method' => 'DELETE','route' => [$delete_route, $value],'style'=>'display:inline']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!} -->
                                        </td>
                                        @break
                                        
                                    @case('designation')
                                        <td>
                                            <span class="text-gray-800 fw-boldest">{{ $designations[$value] }}</span>
                                        </td>
                                        @break                                    
                                    @case('leave_type')
                                        <td>
                                            <span class="text-gray-800 fw-boldest">{{ $leavetypes[$value] }}</span>
                                        </td>
                                        @break
                                    @case('leave_category')
                                        <td>
                                            <span class="text-gray-800 fw-boldest">{{ $leavecategories[$value] }}</span>
                                        </td>
                                        @break
                                    @case('leavestatus')
                                        <td>
                                            <span class="text-gray-800 fw-boldest">{{ $leavestatus[$value] }}</span>
                                        </td>
                                        @break
                                    @default
                                        <td>
                                            <span class="text-gray-800 fw-boldest">{{ $value }}</span>
                                        </td>
                                @endswitch
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
