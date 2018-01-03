
        <?php


    $id = $_POST['id'];

            $sql ="SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_declarelist
                ON disaster_declare.ID = disaster_declarelist.DECLAREID
                INNER JOIN disaster_profile
                ON disaster_declarelist.PROFILEID = disaster_profile.ID
                WHERE disaster_profile.ID = '$id'";

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>