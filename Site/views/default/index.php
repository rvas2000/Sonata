<h2>Главная</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Наименование</th>
            <th>Город</th>
            <th>Масс-маркет</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($complexes as $id => $complex):?>
        <tr>
            <td><?php echo $id; ?></td>
            <td><?php echo $complex['name']; ?></td>
            <td><?php echo $complex['city_id']; ?></td>
            <td><?php echo $complex['mass_market']; ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
