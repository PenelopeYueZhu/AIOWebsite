
  <!-- Form to choose different filters and sorting options -->
  <form class="form-inline" method="post" action="getAllQ.php">';
    <label for="sort">Sort by:</label>';
    <select name="sort_by" class="form-control">';
    <!-- display the options -->
      <option value="qNTO">Newest Question first</option>
      <option value="qOTN">Oldest question first</option>
    </select>

    <!-- Filter drop down list -->
    <label for="category">Filter by category:</label>
    <select name="filter_by" class="form-control">
    <!-- Display all the categories -->
      <option value="0">All</option>
      <?php for( $i = 0 ; $i < count( $_SESSION['categories'] ); $i++ ) {
        echo '<option value="' . ($i+1) . '">' . $_SESSION['categories'][$i]
              . '</option>';
      } ?>
    </select>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
