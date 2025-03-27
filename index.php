<?php
// Define API base URL (Update if deployed)
$api_url = "http://localhost/api/quotes/";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Quotes API</h2>
    
    <!-- Display Quotes -->
    <div class="card mt-3">
        <div class="card-header">All Quotes</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Quote</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="quotesTable"></tbody>
            </table>
        </div>
    </div>

    <!-- Add Quote Form -->
    <div class="card mt-3">
        <div class="card-header">Add New Quote</div>
        <div class="card-body">
            <form id="addQuoteForm">
                <div class="mb-3">
                    <label class="form-label">Quote</label>
                    <input type="text" id="quote" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Author ID</label>
                    <input type="number" id="author_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category ID</label>
                    <input type="number" id="category_id" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Quote</button>
            </form>
        </div>
    </div>

    <!-- Edit Quote Modal -->
    <div class="modal fade" id="editQuoteModal" tabindex="-1" aria-labelledby="editQuoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Quote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editQuoteForm">
                        <input type="hidden" id="edit_quote_id">
                        <div class="mb-3">
                            <label class="form-label">Quote</label>
                            <input type="text" id="edit_quote" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author ID</label>
                            <input type="number" id="edit_author_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category ID</label>
                            <input type="number" id="edit_category_id" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update Quote</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    fetchQuotes();

    // Fetch Quotes
    function fetchQuotes() {
        $.get("<?php echo $api_url; ?>read.php", function(data) {
            let quotes = data.data;
            let output = "";
            $.each(quotes, function(index, quote) {
                output += `
                    <tr>
                        <td>${quote.id}</td>
                        <td>${quote.quote}</td>
                        <td>${quote.author}</td>
                        <td>${quote.category}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editQuote" data-id="${quote.id}" data-quote="${quote.quote}" data-author="${quote.author_id}" data-category="${quote.category_id}" data-bs-toggle="modal" data-bs-target="#editQuoteModal">Edit</button>
                            <button class="btn btn-danger btn-sm deleteQuote" data-id="${quote.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $("#quotesTable").html(output);
        });
    }

    // Add Quote
    $("#addQuoteForm").submit(function(e) {
        e.preventDefault();
        let newQuote = {
            quote: $("#quote").val(),
            author_id: $("#author_id").val(),
            category_id: $("#category_id").val()
        };

        $.post("<?php echo $api_url; ?>create.php", JSON.stringify(newQuote), function(response) {
            alert(response.message);
            fetchQuotes();
            $("#addQuoteForm")[0].reset();
        });
    });

    // Open Edit Modal & Populate Fields
    $(document).on("click", ".editQuote", function() {
        let id = $(this).data("id");
        let quote = $(this).data("quote");
        let author_id = $(this).data("author");
        let category_id = $(this).data("category");

        $("#edit_quote_id").val(id);
        $("#edit_quote").val(quote);
        $("#edit_author_id").val(author_id);
        $("#edit_category_id").val(category_id);
    });

    // Update Quote
    $("#editQuoteForm").submit(function(e) {
        e.preventDefault();
        let updatedQuote = {
            id: $("#edit_quote_id").val(),
            quote: $("#edit_quote").val(),
            author_id: $("#edit_author_id").val(),
            category_id: $("#edit_category_id").val()
        };

        $.ajax({
            url: "<?php echo $api_url; ?>update.php",
            type: "PUT",
            data: JSON.stringify(updatedQuote),
            contentType: "application/json",
            success: function(response) {
                alert(response.message);
                fetchQuotes();
                $("#editQuoteModal").modal("hide");
            }
        });
    });

    // Delete Quote
    $(document).on("click", ".deleteQuote", function() {
        let id = $(this).data("id");
        if (confirm("Are you sure you want to delete this quote?")) {
            $.ajax({
                url: "<?php echo $api_url; ?>delete.php?id=" + id,
                type: "DELETE",
                success: function(response) {
                    alert(response.message);
                    fetchQuotes();
                }
            });
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
