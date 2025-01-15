@foreach($installercards as $idx=>$installercard)
     <tr>
            {{-- <td><input type="checkbox" name="singlechecks" class="form-check-input singlechecks" value="{{$installercard->id}}" /></td> --}}
            <td>
                    {{ $idx + $installercards->firstItem() }}
            </td>
            <td>{{ $installercard->card_number }}</td>
            <td>{{ $installercard->name }}</td>
            <td>{{ $installercard->phone }}</td>
            <td>{{ $installercard->nrc }}</td>
            <td>{{ $installercard->identification_card }}</td>
            {{-- <td>{{ $installercard->totalpoints }}</td> --}}
            {{-- <td>{{ $installercard->totalamount }}</td> --}}
            <td>{{  \Carbon\Carbon::parse($installercard->issued_at)->format('d-m-Y') }}</td>
            <td>{{ $installercard->users->name }}</td>
            <td>
                <div class="custom-switch p-0">
                    <!-- The actual checkbox that controls the switch -->
                    <input type="checkbox" id="customSwitch-{{ $idx + $installercards->firstItem() }}" class="custom-switch-input statuschange-btn" {{ $installercard->status === 1 ? "checked" : "" }} data-id="{{ $installercard->id }}" data-card_number="{{ $installercard->card_number }}"/>
                    <!-- The label is used to style the switch, and clicking it toggles the checkbox -->
                    <label class="custom-switch-label" for="customSwitch-{{ $idx + $installercards->firstItem() }}"></label>
                    <!-- Optional label text next to the switch -->
                </div>
            </td>


            <td class="row border-0">
                    {{-- <a href="javascript:void(0);" class="text-info mr-3 changemodal-btns" data-card_number="{{ $installercard->card_number }}"><i class="fas fa-edit"></i></a> --}}
                    <a href="javascript:void(0);" class="text-info mr-3 editmodal-btns" data-card_number="{{ $installercard->card_number }}" data-name="{{ $installercard->name }}" data-phone="{{ $installercard->phone }}" title="Edit"><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0);" class="text-danger delete-btns" data-id="{{ $installercard->id }}"  data-idx="{{ ++$idx }}" data-card_number="{{ $installercard->card_number }}" title="Delete"><i class="fas fa-trash-alt"></i></a>
            </td>
     </tr>
@endforeach
