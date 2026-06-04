@php $u = $user ?? null; @endphp
<div><label class="label-field">Name</label><input name="name" value="{{ old('name', $u?->name) }}" required class="input-field"></div>
<div><label class="label-field">Email</label><input type="email" name="email" value="{{ old('email', $u?->email) }}" required class="input-field"></div>
<div><label class="label-field">Password {{ $u ? '(leave blank to keep)' : '*' }}</label><input type="password" name="password" {{ $u ? '' : 'required' }} class="input-field"></div>
<div><label class="label-field">Role</label><select name="role" class="input-field"><option value="admin" @selected(old('role', $u?->role) === 'admin')>admin</option></select></div>
<label class="flex gap-2 text-sm cursor-pointer"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $u?->is_active ?? true))> Active</label>
