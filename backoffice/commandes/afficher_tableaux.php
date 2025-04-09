<?php
function afficherTableau($data, $columns, $titles, $editLink, $deleteLink = null, $associations = []) {
    echo "<table class='min-w-full table-auto border-collapse'>";
    echo "<thead class='bg-gray-200'>";
    echo "<tr>";
    foreach ($titles as $title) {
        echo "<th class='px-6 py-3 text-left text-sm font-semibold text-gray-700'>{$title}</th>";
    }
    echo "<th class='px-6 py-3 text-center text-sm font-semibold text-gray-700'>Actions</th>";
    echo "</tr>";
    echo "</thead>";

    echo "<tbody class='bg-white'>";
    foreach ($data as $row) {
        echo "<tr class='border-b'>";
        foreach ($columns as $column) {
            echo "<td class='px-6 py-4 text-sm text-gray-800'>";
            
            if ($column == 'lien') {
                $lien = trim($row[$column] ?? '');
                echo $lien !== '' ? "<a href='" . htmlspecialchars($lien) . "' target='_blank'>" . htmlspecialchars($lien) . "</a>" : '-';
            } elseif (in_array($column, ['img', 'photo', 'picto'])) {
                // ✅ Correction : s'assurer que seul le nom de fichier est utilisé
                $src = $row[$column];

                echo "<img src='../{$src}' alt='image' class='w-20 h-auto rounded shadow mb-1' />";
                echo "<br><small class='text-gray-500 text-xs'>../{$src}</small>";
            } else {
                echo isset($row[$column]) ? htmlspecialchars($row[$column]) : '-';
            }
            echo "</td>";
        }

        echo "<td class='px-6 py-4 text-center'>
                <div class='flex items-center justify-center space-x-4'>";

        echo "<a href='" . $editLink . $row['id'] . "' class='text-gray-500 hover:text-gray-700' title='Modifier'>
                <i class='fas fa-pen'></i>
              </a>";

        if ($deleteLink) {
            echo "<a href='" . $deleteLink . $row['id'] . "' class='text-gray-500 hover:text-gray-700' title='Supprimer' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');\">
                    <i class='fas fa-trash'></i>
                  </a>";
        }

        echo "</div></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}
?>
