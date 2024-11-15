<x-layout>

    <!-- Button to Open Modal -->
    <div class="text-end ">
        <button type="button" style="margin-right:1rem; margin-top:1rem" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#myModal">
            Add
        </button>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div id="window-container" class="row all-window-queue ">

    </div>

</x-layout>
