@extends ('auth.layouts')
@section ('content')
<table>
    <tr>
        <td>Name</td>
        <td>Email</td>
        <td>Photo</td>
        <td>Action</td>
    </tr>
    @foreach ($userss as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><img src="{{ asset('storage/' . $user->photo) }}" style="width : 100px" alt="photo {{ $user->name }}"></td>
            <td>
                <button>
                    <a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary float-end">Edit Photo</a>
                </button>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                    @method('DELETE') {{ csrf_field() }}
                    <br />
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
<div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
</div>
@endsection