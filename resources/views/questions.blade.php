<!DOCTYPE html>
<html>
<head>
    <title>Question Bank</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Question Bank</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewQuestion"> Create New Question</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Question</th>
                <th>Option A</th>
                <th>Option B</th>
                <th>Option C</th>
                <th>Option D</th>
                <th>Answer</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

git config --global user.name "Taiwo Ogunyemi"
git config --global user.email "ogunyemitaiwojohn@gmail.com"
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="questionForm" name="questionForm" class="form-horizontal">
                   <input type="hidden" name="question_id" id="question_id">


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Question</label>
                        <div class="col-sm-12">
                            <textarea id="question" name="question" required="" placeholder="Question" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="option_a" class="col-sm-2 control-label">Option A</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="option_a" name="option_a" placeholder="Option A" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="option_b" class="col-sm-2 control-label">Option B</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="option_b" name="option_b" placeholder="Option B" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="option_c" class="col-sm-2 control-label">Option C</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="option_c" name="option_c" placeholder="Option C" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="option_d" class="col-sm-2 control-label">Option D</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="option_d" name="option_d" placeholder="Option D" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="option_a" class="col-sm-2 control-label">Answer</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="answer" name="answer" placeholder="Answer" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">
  $(function () {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('question.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'question', name: 'question'},
            {data: 'option_a', name: 'option_a'},
            {data: 'option_b', name: 'option_b'},
            {data: 'option_c', name: 'option_c'},
            {data: 'option_d', name: 'option_d'},
            {data: 'answer', name: 'answer'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewQuestion').click(function () {
        $('#saveBtn').val("create-question");
        $('#question_id').val('');
        $('#questionForm').trigger("reset");
        $('#modelHeading').html("Create New Question");
        $('#ajaxModel').modal('show');
    });

    $('body').on('click', '.editQuestion', function () {
      var question_id = $(this).data('id');
      $.get("{{ route('question.index') }}" +'/' + question_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Question");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#question_id').val(data.id);
          $('#question').val(data.question);
          $('#option_a').val(data.option_a);
          $('#option_b').val(data.option_b);
          $('#option_c').val(data.option_c);
          $('#option_d').val(data.option_d);
          $('#answer').val(data.answer);
      })
   });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');

        $.ajax({
          data: $('#questionForm').serialize(),
          url: "{{ route('question.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {

              $('#questionForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

    $('body').on('click', '.deleteQuestion', function () {

        var question_id = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
            type: "DELETE",
            url: "{{ route('question.store') }}"+'/'+question_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

  });
</script>
</html>
