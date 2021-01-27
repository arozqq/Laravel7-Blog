@extends('layouts.app')

@section('title', $post->title)
@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
         @if($post->thumbnail)
          <a href="{{route('posts.show', $post->slug)}}">
            <img style="height: 420px;object-fit: contain;object-position: center;" src="{{$post->takeImg}}" class="rounded w-100">
          </a>  
          @endif          
          <h1 class="card-title"> {{$post->title}} </h1>
          <div class="text-secondary">
          <a href="/categories/{{$post->category->slug}}"> {{$post->category->name}}</a> &middot; {{$post->created_at->format("d F, Y")}}
          &middot;
          @foreach ($post->tags as $tag)
          <a href="/tags/{{$tag->slug}}">{{$tag->name}}</a>
          @endforeach
          </div>

          <div class="media my-3">
            <img width="60" class="rounded-circle mr-3" src="{{$post->author->gravatar()}}"> 
            <div class="media-body">
              <div>
                {{$post->author->name}}
              </div>
              {{'@' . $post->author->username}}
            </div>
          </div>
          <p class="card-text mt-3" style="text-align: justify; text-indent:1.5rem">{!! nl2br($post->body) !!}</p>
       </div>
       <div class="card-footer">
         
         <!-- Button trigger modal -->
         {{-- logika if is me --}}
         {{-- {{-- @if(auth()->user()->id == $post->user_id) atau seperti dibawah  --}}
        {{-- @if(auth()->user()->is($post->author)) --}}
        {{-- bisa jg pake policy --}}
         @can('delete', $post)
         <div class="flex mt-3">
           <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal">
             Delete
            </button>
            <a href="/posts/{{$post->slug}}/edit" class="btn btn-sm btn-success">Edit</a>
         </div>
          @endcan
       </div>
      </div>
    </div>

    <div class="col-md-4">
    @foreach ($posts as $post)
    <div class="card mb-4">
     <div class="card-body">
       <div>
         <a href="{{route('categories.show', $post->category->slug)}}" class="text-secondary"><small>{{$post->category->name}}</small></a>
         -
         @foreach ($post->tags as $tag)
         <a href="{{route('tags.show', $tag->slug)}}" class="text-secondary"><small>{{$tag->name}}</small></a>
         @endforeach
       </div>
           <h5>
             <a href="{{route('posts.show', $post->slug)}}" class="card-title text-dark">{{$post->title}}</a>
           </h5>
         <div class="text-secondary my-3">
           {{Str::limit($post->body, 130, '.')}}
         </div>

         <a href="/posts/{{$post->slug}}">Read More</a>

         <div class="d-flex justify-content-between align-items-center mt-3">
           <div class="media align-items-center">
             <img width="40" class="rounded-circle mr-3" src="{{$post->author->gravatar()}}"> 
             <div class="media-body">
               {{$post->author->name}}
             </div>
             
           </div>
         </div>
        
       </div>
       {{-- <div class="card-footer d-flex justify-content-between"> --}}

       {{-- ini buat time stamp--}}
       {{-- Publish on {{$post->created_at->format("d F, Y")}} --}}

       {{-- ini waktu publish --}}
         {{-- Publish on {{$post->created_at->diffForHumans()}} --}}
         
         {{-- sama kaya if auth:check --}}
         {{-- @can ini untuk policy sama aja kaya if auth--}}
         {{-- @can('update', $post)
         <a href="/posts/{{$post->slug}}/edit" class="btn btn-sm btn-success">Edit</a>
         @endcan --}}
       {{-- </div> --}}
     </div>
    @endforeach
    </div>

  </div>
</div>


 <!-- Modal Delete Section-->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Apakah anda ingin mengahapus ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/posts/{{$post->slug}}/delete" method="post">
        @csrf
        @method("DELETE")
      <div class="modal-body">
        <div>
          <div>Judul : {{ $post->title }}</div>
          <div>Published on : {{ $post->created_at->format("d F, Y") }}</div>
        </div>
          
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button class="btn btn-danger" type="submit">Delete</button>
      </form>
      </div>
    </div>
  </div>

@endsection