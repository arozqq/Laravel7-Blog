@extends('layouts.app')

@section('content')
<div class="container">
    
  <div class="justify-content-between">
    <div>
      @isset($category)
    <h4>Category : {{$category->name}}</h4>
      @endisset 

      @isset($tag)
      <h4>Tags : {{$tag->name}}</h4>
        @endisset 

      @if (!isset($tag) && !isset($category))
          <h4>Welcome to my blog</h4>
      @endif
      <hr>
    </div>
    <div>
    
    </div>
  </div>


  <div class="row justify-content-between">
    {{-- <div class="col-md-7"> --}}
    @forelse ($posts as $post)
    <div class="col-sm-4">

        <div class="card mb-4 shadow">
            @if($post->thumbnail)
          <a href="{{route('posts.show', $post->slug)}}">
            <img style="height: 250px;object-fit: contain;object-position: center;" src="{{$post->takeImg}}" class="card-img-top">
          </a>  
          @endif

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
                {{Str::limit($post->body, 125, '.')}}
              </div>

              <a href="/posts/{{$post->slug}}">Read More</a>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="media align-items-center">
                  <img width="40" class="rounded-circle mr-3" src="{{$post->author->gravatar()}}"> 
                  <div class="media-body">
                    {{'@' . $post->author->username}}
                  </div>
                  
                </div>
                    <div class="text-secondary">
                    <small>Publish on {{$post->created_at->format("d F, Y")}}</small>
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
        </div>
          @empty
          <div class="col-md-6">
            <div class="alert alert-info">There's no post</div>
        </div>
        
        @endforelse
        
        {{-- </div> --}}
    </div>
    {{-- paginate --}}
    {{$posts->links()}}


</div>



    
@endsection
