@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">スケジュール登録</div>

                    <div class="card-body">
                        <form action="{{ route('schedules.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">タイトル</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="description">概要</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="start_date">開始日</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="form-group">
                                <label for="end_date">終了日</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            <button type="submit" class="mt-3 btn btn-primary">登録</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
