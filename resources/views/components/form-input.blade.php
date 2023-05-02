<div class="mb-3"}>
    <label for="{{$fname}}" class="form-label">{{$flabel}}</label>
    <input value="{{$fvalue}}"
        type="{{$ftype}}" 
        class="form-control" 
        name="{{$fname}}" 
        placeholder="{{$flabel}}" {{$frequired}} {{$fdisable}}>
        <?php $err = $fname; ?>
        @if ($errors->has($err))
            <span class="text-danger text-left"><?php echo $errors->first($err); ?></span>
        @endif
</div>