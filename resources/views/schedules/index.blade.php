{{-- @extends('layouts.app') --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://example.com/fontawesome/v6.3.0/js/all.js" data-family-prefix="icon"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        @section('javascript')
        <script src="/js/confirm.js"></script>
        @endsection
    @yield('javascript')
    <!-- Fonts -->
   <link rel="dns-prefetch" href="//fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="/css/layout.css" rel="stylesheet">
</head>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container navbar-brand mb-0 h1">
                カレンダー
        </div>
    </nav>
    <div class="row">
    <div class="col-sm-12">
        <div class="card mb-0">
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
</div>
{{-- @section('content') --}}

{{-- @endsection --}}

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
{{-- @endsection --}}