@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('schedules.create') }}" class="btn btn-primary mb-3">スケジュール登録</a>
                <div class="card">
                    <div class="card-header">スケジュール</div>

                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js"></script>
    <style>
        {{-- 全体的なテキストの色と下線変更 --}}
        .fc-col-header-cell-cushion,
        .fc-daygrid-day-number,
        .fc-daygrid-day-top {
            color: #333;
            text-decoration: none;
        }
        /* 曜日の色 */
        .fc-col-header-cell.fc-day-mon,
        .fc-col-header-cell.fc-day-tue,
        .fc-col-header-cell.fc-day-tue,
        .fc-col-header-cell.fc-day-wed,
        .fc-col-header-cell.fc-day-thu,
        .fc-col-header-cell.fc-day-fri {
            background-color: #eee;
        }
        .fc-col-header-cell.fc-day-sat {
            background-color: #cce3f6;
        }
        .fc-col-header-cell.fc-day-sun {
            background-color: #f4d0df;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            const events = @json($events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                height: 'auto',
                firstDay: 1,
                headerToolbar: {
                    left: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
                    center: "title",
                    right: "today prev,next"
                },
                buttonText: {
                    today: '今月',
                    month: '月',
                    week: '週',
                    day: '日',
                    list: 'リスト'
                },
                noEventsContent: 'スケジュールはありません',
                fixedWeekCount: false, // 6週目を表示する場合は「true」に設定
                showNonCurrentDates: false, //当月以外はグレー表示
                // initialDate: '2023-04-01', // 初期表示する年月を指定する
                events: events,
                editable: true,
                eventDrop: function(info) {
                    updateEvent(info);
                },
                eventResize: function(info) {
                    updateEvent(info);
                },
                eventMouseEnter(info) {
                    $(info.el).popover({
                        title: info.event.title,
                        content: info.event.extendedProps.description,
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body',
                        html: true
                    });
                },
                dayCellDidMount: function(cellInfo) {
                    // If the day of the week is Saturday (6) or Sunday (0)
                    if (cellInfo.date.getDay() === 6 || cellInfo.date.getDay() === 0) {
                        cellInfo.el.style.backgroundColor = '#F5F5F5'; // Change to any color you like
                    }
                }
            });
            calendar.render();
        });
        function updateEvent(info){
            const id = info.event.id;
            const start = info.event.start.toLocaleString('ja-JP');
            const end = info.event.end.toLocaleString('ja-JP');

            $.ajax({
                url: '/schedules/' + id + '/updateByCalendar',
                type: 'PUT',
                data: {
                    id: info.event.id,
                    start_date: start,
                    end_date: end,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        console.log('Event Updated');
                    } else {
                        alert('Error occurred');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('AJAX error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }
    </script>
@endsection
