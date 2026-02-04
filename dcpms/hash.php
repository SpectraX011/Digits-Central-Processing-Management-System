<?php
echo "Student password hash: " . password_hash("student123", PASSWORD_DEFAULT) . "<br><br>";
echo "Digit Officer password hash: " . password_hash("officer123", PASSWORD_DEFAULT) . "<br>";