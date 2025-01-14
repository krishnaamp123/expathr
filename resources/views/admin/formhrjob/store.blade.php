{{-- @extends('admin.layout.app')
@section('title', 'Add Form')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Form</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeForm') }}" method="post">
                        @csrf

                        <!-- Form Name -->
                        <div class="form-group">
                            <label>Form Name</label>
                            <input type="text" name="form_name" class="form-control" placeholder="Enter Form Name">
                            @error('form_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Questions and Answers -->
                        <div id="questions-container">
                            <div class="question-item">
                                <div class="form-group">
                                    <label>Question</label>
                                    <input type="text" name="questions[0][question_name]" class="form-control" placeholder="Enter Question">
                                    @error('questions.*.question_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="answers-container">
                                    <div class="form-group">
                                        <label>Answer</label>
                                        <input type="text" name="questions[0][answers][0][answer_name]" class="form-control" placeholder="Enter Answer">
                                        <label class="form-check-label">
                                            <input type="radio" name="questions[0][correct_answer]" value="0" class="form-check-input"> Correct Answer
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>Answer</label>
                                        <input type="text" name="questions[0][answers][1][answer_name]" class="form-control" placeholder="Enter Answer">
                                        <label class="form-check-label">
                                            <input type="radio" name="questions[0][correct_answer]" value="1" class="form-check-input"> Correct Answer
                                        </label>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-secondary add-answer">Add Answer</button>
                                <hr>
                            </div>
                        </div>

                        <button type="button" id="add-question" class="btn btn-sm btn-primary">Add Question</button>
                        <hr>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let questionIndex = 0;

        document.getElementById('add-question').addEventListener('click', function () {
            questionIndex++;
            const questionItem = `
                <div class="question-item">
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" name="questions[${questionIndex}][question_name]" class="form-control" placeholder="Enter Question">
                    </div>
                    <div class="answers-container">
                        <div class="form-group">
                            <label>Answer</label>
                            <input type="text" name="questions[${questionIndex}][answers][0][answer_name]" class="form-control" placeholder="Enter Answer">
                            <label class="form-check-label">
                                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="0" class="form-check-input"> Correct Answer
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Answer</label>
                            <input type="text" name="questions[${questionIndex}][answers][1][answer_name]" class="form-control" placeholder="Enter Answer">
                            <label class="form-check-label">
                                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="1" class="form-check-input"> Correct Answer
                            </label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary add-answer">Add Answer</button>
                    <hr>
                </div>
            `;
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionItem);
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('add-answer')) {
                const questionItem = e.target.closest('.question-item');
                const answersContainer = questionItem.querySelector('.answers-container');
                const answerCount = answersContainer.querySelectorAll('.form-group').length;

                const newAnswer = `
                    <div class="form-group">
                        <label>Answer</label>
                        <input type="text" name="questions[${questionIndex}][answers][${answerCount}][answer_name]" class="form-control" placeholder="Enter Answer">
                        <label class="form-check-label">
                            <input type="radio" name="questions[${questionIndex}][correct_answer]" value="${answerCount}" class="form-check-input"> Correct Answer
                        </label>
                    </div>
                `;
                answersContainer.insertAdjacentHTML('beforeend', newAnswer);
            }
        });
    </script>
@endsection --}}
