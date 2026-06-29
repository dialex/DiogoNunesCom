---
title: "Insert and read images on SQL Server using C#"
description: "Code examples in C# about converting byte[] to base64 and SQL Server's varbinary. This allows you to save images to your DB and get them back again."
pubDate: "2017-05-22T13:50:00"
heroImage: "../../assets/blog/uploads/2016/09/archive-005.jpg"
tags: ["c#", "coding"]
categories: ["Technology"]
---

## Writing to SQL Server database

**base64 to byte\[\]**

```
// Restore the byte array.
byte[] newBytes = Convert.FromBase64String(strBase64);
```

[Source](https://msdn.microsoft.com/en-us/library/system.convert.frombase64string%28v=vs.110%29.aspx)

**byte\[\] to SQL varbinary**

```
byte[] buffer = File.ReadAllBytes("Path/to/your/image/");
SqlCommand command = new SqlCommand();
command.Text="INSERT INTO YOUR_TABLE_NAME (image) values (@image)";
command.Parameters.AddWithValue("@image",buffer);
command.ExecuteNonQuery();
```

[Source](http://stackoverflow.com/a/7325145/675577%E2%80%8B%E2%80%8B)

## Reading from SQL Server database

**Scalar to byte\[\]**

```
const string sql = "SELECT data FROM files WHERE name = @name";
using (var conn = db.CreateConnection())
using (var cmd = conn.CreateTextCommand(sql))
{
    cmd.AddInParam("name", DbType.String, name);
    conn.Open();
    return cmd.ExecuteScalar() as byte[];
}
```

[Source](http://stackoverflow.com/questions/22771133/insert-byte-array-into-sql-server-from-c-sharp-and-how-to-retrieve-it/22771540#22771540)

**byte\[\] to base64**

```
// Convert the array of bytes to a base 64 string.
byte[] bytes = { 2, 4, 6, 8, 10, 12, 14, 16, 18, 20 };
String strBase64 = Convert.ToBase64String(bytes);

// Restore the byte array.
byte[] newBytes = Convert.FromBase64String(s);
```

[Source](https://msdn.microsoft.com/en-us/library/system.convert.frombase64string%28v=vs.110%29.aspx)
