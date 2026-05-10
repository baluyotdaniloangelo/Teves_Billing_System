<!-- VIEW PAYMENT GALLERY MODAL -->
<div class="modal fade"
     id="ViewPaymentGalery"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

            <!-- MODAL HEADER -->
            <div class="modal-header border-0 bg-light px-4 py-3">

                <div class="d-flex align-items-center">

                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:45px;height:45px;">

                        <i class="bi bi-images text-success fs-5"></i>

                    </div>

                    <div>

                        <h5 class="modal-title fw-bold mb-0">
                            Payment Information
                        </h5>

                        <small class="text-muted">
                            Uploaded payment references and attachments
                        </small>

                    </div>

                </div>

                <!-- CLOSE BUTTON -->
                <button type="button"
                        class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                        data-bs-dismiss="modal"
                        style="width:35px;height:35px;">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <!-- MODAL BODY -->
            <div class="modal-body bg-light p-4">

                <!-- CAROUSEL -->
                <div id="carouselExampleCaptions"
                     class="carousel slide"
                     data-bs-ride="false">

                    <!-- INDICATORS -->
                    <div class="carousel-indicators">

                    </div>

                    <!-- SLIDES -->
                    <div class="carousel-inner rounded-4 overflow-hidden border bg-white shadow-sm"
                         style="min-height:650px;">

                    </div>

                    <!-- PREVIOUS -->
                    <button class="carousel-control-prev"
                            type="button"
                            data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">

                        <div class="bg-dark bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center"
                             style="width:50px;height:50px;">

                            <span class="carousel-control-prev-icon"
                                  aria-hidden="true"></span>

                        </div>

                        <span class="visually-hidden">
                            Previous
                        </span>

                    </button>

                    <!-- NEXT -->
                    <button class="carousel-control-next"
                            type="button"
                            data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">

                        <div class="bg-dark bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center"
                             style="width:50px;height:50px;">

                            <span class="carousel-control-next-icon"
                                  aria-hidden="true"></span>

                        </div>

                        <span class="visually-hidden">
                            Next
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>