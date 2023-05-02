<div class="mb-3">
    <label for="{{$fname}}" class="form-label">{{$flabel}}</label>
    <textarea                         
        class="form-control" 
        name="{{ $fname }}" 
        placeholder="{{ $flabel }}" {{$frequired}} {{$fdisable}}>{{$fvalue}}</textarea>
        <?php $err = $fname; ?>
        @if ($errors->has($err))
            <span class="text-danger text-left"><?php echo $errors->first($err); ?></span>
        @endif
</div>