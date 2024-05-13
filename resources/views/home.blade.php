@extends('layouts.app')

@section('content')
<style>
    .ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable, .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners {
    border-radius: var(--ck-border-radius);
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    height: 200px;
}
</style>
<!-------------------------Modal---------------------------------------------->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are You sure to delete this Note?
      </div>
      <div class="modal-footer">
        <form method="post" action="{{ route('deletenote') }}">
            @csrf
            <input type="text" name="note_id" hidden id="note_id">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!----------------- Modal ----------------------------------------------------->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Write a Note</div>
                <div class="card-body">
                <form method="POST" action="{{ route('newnote') }}" enctype="multipart/form-data">
                        @csrf
                        @if($message = session('message'))
                                    <div class="alert alert-success">
                                        
                                        {{ $message }}
                                        
                                    </div>
                                    @endif
                                    @if($message = session('errormessage'))
                                    <div class="alert alert-danger">
                                        
                                        {{ $message }}
                                        
                                    </div>
                                    @endif
                        <div class="form-group row">
                            <label for="note" class="col-md-4 col-form-label">{{ __('Note') }}</label>

                            <div class="col-md-12">
                                <textarea id="editor" class="form-control @error('note') is-invalid @enderror" name="note" row="3">{{ old('note') }}</textarea>
                                @error('note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label">Upload document (Optional )</label>
                            <div class="col-md-12">
                                <input id="upload" type="file" class="form-control @error('upload') is-invalid @enderror" name="upload">
                                @error('upload')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                      
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Note') }}
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-danger">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
<?php
use Illuminate\Support\Str;
    $user_id = Auth::user()->id;
    $notes_det =DB::connection('mysql_T')->table('notes')
                ->where('user_id',$user_id)
                ->orderby('id','desc')->get();

    if($notes_det->isNotEmpty()){
       
       
?>
<div class="wrapper-content mt-4">
    <div class="container">
        <div class="row p-4">
            <h2 class="whitetext">You Notes</h2>
        </div>
    </div>

    <div class="container">
        <div class="row mt-4">
            @foreach($notes_det as $dt)
            <div class="col-md-4 p-2">
                <div class="card">
                    <div class="card-header">
                        {{ date('d-m-Y',strtotime($dt->created_at)) }}
                        <a href="{{ route('note',['id'=>$dt->id]) }}" class="btn btn-success float-right ml-2"><i class="fas fa-eye"></i></a>
                        <a href="#" class="btn btn-danger float-right" data-id="{{ $dt->id }}" id="deletebtn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-trash-alt"></i></a>
                    </div>
                    <div class="card-body">
                        <p>{!! Illuminate\Support\Str::limit($dt->note_text, $limit = 30, $end = '...') !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<?php
    
}

?>
@endsection
