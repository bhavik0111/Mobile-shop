 
<table border="1" cellpadding="5" cellspacing="5">
    <tr>
        <td>
            <a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'";} ?>>Previous</a>
        </td>
        <?php for($counter = 1; $counter <= $total_no_of_pages; $counter++){
                if($counter == $page_no){
                    echo "<td><a>$counter</a></li>";
                }else{
                    echo "<td><a href='?page_no=$counter'>$counter</a></td>";
                }
             }
        ?>
        <td>
            <a <?php if($page_no < $total_no_of_pages){ echo "href='?page_no=$next_page'";} ?>>Next</a>
        </td>    
    </tr>
</table>