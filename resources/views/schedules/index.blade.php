@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スケジュール</div>

                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<style>
    {{-- 全体的なテキストの色と下線変更 --}}
    .fc-col-header-cell-cushion,
    .fc-daygrid-day-number {
        color: #333;
        text-decoration: none;
    }
    .fc-col-header-cell.fc-day-sat {
        background-color: #cce3f6;
    }
    .fc-col-header-cell.fc-day-sun {
        background-color: #f4d0df;
    }
</style>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const events = @json($events);
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                locale: 'ja',
                height: '500px',
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
                showNonCurrentDates: false, 
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
                editable: true,
                eventDrop: function(info) {
                    updateEvent(info);
                },
                eventResize: function(info) {
                    updateEvent(info);
                },
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