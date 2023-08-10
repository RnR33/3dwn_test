<div>
    <div class="col-md-8 mb-2">
        @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('success') }}
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('error') }}
        </div>
        @endif
        @if($addNotification)
        @include('livewire.create')
        @endif
        @if($updateNotification)
        @include('livewire.update')
        @endif
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @if(!$addNotification)
                <button wire:click="addNotification()" class="btn btn-primary btn-sm float-right">
                    Add New Notification</button>
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($notifications) > 0)
                            @foreach ($notifications as $notification)
                            <tr>
                                <td>
                                    {{$notification->title}}
                                </td>
                                <td>
                                    {{$notification->description}}
                                </td>
                                <td>
                                    <button wire:click="editNotification({{$notification->id}})"
                                        class="btn btn-primary btn-sm">Edit</button>
                                    <button wire:click="deleteNotification({{$notification->id}})"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3" align="center">
                                    No Notifications Found.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>