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
<?php
    $note_id = request()->route('id');
    $notes_det =DB::connection('mysql_T')->table('notes')
                ->where('id',$note_id)
                ->first();
       
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Note</div>
                <div class="card-body">
                <form method="POST" action="{{ route('editnote',['id'=>$note_id]) }}" enctype="multipart/form-data">
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
                                <textarea id="editor" class="form-control @error('note') is-invalid @enderror" name="note" row="3">{!! $notes_det->note_text !!}</textarea>
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
                                    {{ __('Update Note') }}
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

@endsection
