<style>
    .pagination {
        margin-top: 20px;
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        color: #007bff;
        border: 1px solid #007bff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: #007bff;
        color: white;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination .page-item.disabled .page-link {
        color: #ddd;
        pointer-events: none;
        background-color: #f8f9fa;
    }
    .form-select {
    width: 100%;
    padding: 5px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

</style>


<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" id="search-input" class="form-control me-2" placeholder="Search...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Date from</th>
                                <th>Date to</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="data-body">
                            <!-- Data will be appended here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul class="pagination justify-content-end" id="pagination">
                        <!-- Pagination links will be appended here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

