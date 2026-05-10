<!-- LOGOUT MODAL -->
<div class="modal fade"
     id="logoutModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header border-0 pb-0 px-4 pt-4">

                <div class="w-100 text-center">

                    <!-- ICON -->
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:80px;height:80px;">

                        <i class="bi bi-box-arrow-right text-warning fs-1"></i>

                    </div>

                    <!-- TITLE -->
                    <h4 class="fw-bold mb-2">

                        Logout Session

                    </h4>

                    <!-- SUBTITLE -->
                    <p class="text-muted mb-0">

                        You are about to end your current session.

                    </p>

                </div>

            </div>

            <!-- BODY -->
            <div class="modal-body px-4 py-3 text-center">

                <div class="alert alert-warning border-0 rounded-4 mb-0">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-exclamation-triangle-fill text-warning me-3 fs-5"></i>

                        <div class="text-start">

                            <div class="fw-semibold mb-1">

                                Ready to Logout?

                            </div>

                            <small class="text-muted">

                                Select <strong>Logout</strong> below if you are ready
                                to end your current session securely.

                            </small>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">

                <!-- CLOSE -->
                <button type="button"
                        class="btn btn-light rounded-3 px-4"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-2"></i>
                    Cancel

                </button>

                <!-- LOGOUT -->
                <a class="btn btn-danger rounded-3 shadow-sm px-4"
                   href="/logout">

                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout

                </a>

            </div>

        </div>

    </div>

</div>