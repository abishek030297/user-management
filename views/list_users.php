<div class="user-list">
    <h2>Users List</h2>
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <div class="user-card <?php echo $user['type']; ?>">
                <h3><?php echo htmlspecialchars($user['name']); ?> 
                    <small>(<?php echo ucfirst($user['type']); ?>)</small>
                </h3>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <?php if ($user['type'] === User::TYPE_STUDENT): ?>
                    <p><strong>Grade:</strong> <?php echo htmlspecialchars($user['grade']); ?></p>
                    <p><strong>Major:</strong> <?php echo htmlspecialchars($user['major']); ?></p>
                <?php elseif ($user['type'] === User::TYPE_TEACHER): ?>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($user['subject']); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($user['experience']); ?> years</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>