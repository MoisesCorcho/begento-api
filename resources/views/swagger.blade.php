<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Begento API — Documentación Swagger / OpenAPI</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui.css" />
    <style>
        body { margin: 0; background: #fafafa; }
        .topbar { display: none; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui-bundle.js" crossorigin></script>
    <script>
        window.onload = () => {
            window.ui = SwaggerUIBundle({
                url: '/docs/openapi.yaml',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                ],
            });
        };
    </script>
</body>
</html>
