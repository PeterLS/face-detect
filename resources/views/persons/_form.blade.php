@csrf

<div class="mb-3">
    <label for="last_name" class="form-label">Last name</label>
    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $person->last_name ?? '') }}"
           class="form-control @error('last_name') is-invalid @enderror" required maxlength="255"/>
    @error('last_name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="first_name" class="form-label">First name</label>
    <input type="text" name="first_name" id="first_name"
           value="{{ old('first_name', $person->first_name ?? '') }}"
           class="form-control @error('first_name') is-invalid @enderror" required maxlength="255"/>
    @error('first_name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check">
    <input class="form-check-input" type="checkbox" value="1" name="is_active"
           id="flexCheckDefault" @checked(old('is_active', $person->is_active ?? TRUE))/>
    <label class="form-check-label" for="flexCheckDefault">
        Is active
    </label>
</div>

<div class="text-center">
    <button class="btn btn-success">Save</button>
</div>
