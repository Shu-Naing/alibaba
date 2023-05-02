<div class="mb-3">
    <label for="{{$fname}}" class="form-label">{{$flabel}}</label>
    <select class="form-control" 
        name="{{$fname}}" {{$frequired}} {{$fdisable}}>
        <option value="">Select {{$flabel}}</option>       
        @foreach($foptions as $key=>$value)
            <option value="{{ $key }}"
            <?php if($key == $fvalue){echo 'selected';} ?>
            >{{ $value }}</option>
        @endforeach
    </select>
    <?php $err = $fname; ?>
    @if ($errors->has($err))
        <span class="text-danger text-left"><?php echo $errors->first($err); ?></span>
    @endif
</div>