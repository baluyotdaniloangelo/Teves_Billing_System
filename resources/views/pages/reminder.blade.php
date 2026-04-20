@extends('layouts.layout')
@section('content')

<main id="main" class="main">
    <section class="section">

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">{{ $title }}</h5>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#CreateReminderModal">
                        <i class="bi bi-plus-circle"></i> Add Reminder
                    </button>
                </div>
            </div>

            <div class="card-body">
                <table class="table dataTable display nowrap cell-border" id="reminderTable" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Reminder Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>

        <!-- ================= CREATE MODAL ================= -->
        <div class="modal fade" id="CreateReminderModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Create Reminder</h5>
                        <button class="btn btn-danger" data-bs-dismiss="modal">X</button>
                    </div>

                    <div class="modal-body">
                        <form id="reminderFormNew">

                            <div class="row mb-2">
                                <label class="col-sm-3">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" name="reminders_title" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-3">Content</label>
                                <div class="col-sm-9">
                                    <textarea name="reminders_content" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-3">Reminder Date</label>
                                <div class="col-sm-9">
                                    <input type="datetime-local" name="reminder_date" class="form-control" required>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" id="saveReminder">Save</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= UPDATE MODAL ================= -->
        <div class="modal fade" id="UpdateReminderModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Update Reminder</h5>
                        <button class="btn btn-danger" data-bs-dismiss="modal">X</button>
                    </div>

                    <div class="modal-body">
                        <form id="reminderFormEdit">

                            <input type="hidden" id="update_reminder_id">

                            <div class="row mb-2">
                                <label class="col-sm-3">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" id="update_reminders_title" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-3">Content</label>
                                <div class="col-sm-9">
                                    <textarea id="update_reminders_content" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-3">Reminder Date</label>
                                <div class="col-sm-9">
                                    <input type="datetime-local" id="update_reminder_date" class="form-control" required>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" id="updateReminder">Update</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= DELETE MODAL ================= -->
        <div class="modal fade" id="DeleteReminderModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Delete Reminder</h5>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete this reminder?
                        <br><br>
                        <strong id="delete_reminder_title"></strong>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" id="confirmDeleteReminder">Delete</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>

    </section>
</main>

@endsection