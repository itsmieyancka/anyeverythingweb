@extends('layouts.app')

@section('content')
    <h1>All Categories</h1>


        @foreach ($categories as $category)
            <li>
                <a href="{{ route('categories.show', $category->slug) }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
