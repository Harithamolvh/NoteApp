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
    if(!empty($notes_det)){
       
?>
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
            <input type="text" name="note_id" hidden value="{{ $note_id }}">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!----------------- Modal ----------------------------------------------------->
<div class="wrapper-content mt-4">
    <div class="container">
        <div class="row p-4">
            <h2 class="whitetext">You Notes</h2>
        </div>
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

    </div>
   
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ date('d-m-Y',strtotime($notes_det->created_at)) }}
                        <a href="{{ route('home') }}" class="btn btn-dark float-right ml-2"><i class="fas fa-home"></i></a>
                        <a href="{{ route('noteedit',['id'=>$notes_det->id]) }}" class="btn btn-secondary float-right ml-2"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-danger float-right" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-trash-alt"></i></a>
                    </div>
                    <div class="card-body">
                        <p>{!! $notes_det->note_text !!}</p>
                        @if(!empty($notes_det->file_upload))
                        <a href="{{ asset('uploads/'.$notes_det->file_upload) }}" target="_blank"><img src="{{ asset('pdf.png') }}" class="img-fluid" style="width:10%;"></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    
}

?>
@endsection
