{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-description', 'Toutes vos notifications')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mes notifications</h3>

        @if($notifications->isEmpty())
            <div class="p-10 text-center text-gray-500">
                Aucune notification pour le moment.
            </div>
        @else
            <ul class="space-y-2">
                @foreach($notifications as $notification)
                    <li class="p-4 border rounded-lg {{ $notification->is_read ? 'bg-gray-50' : 'bg-white border-indigo-100 shadow-sm' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $notification->title ?? 'Notification' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($notification->message ?? '-', 160) }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->is_read)
                                <span class="text-xs font-semibold text-indigo-600">Non lu</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
