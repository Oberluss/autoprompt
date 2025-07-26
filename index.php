<?php
// Procesar el formulario si se envió
$promptGenerated = false;
$promptContent = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    // Recopilar datos del formulario
    $projectName = $_POST['project_name'] ?? '';
    $githubUser = $_POST['github_user'] ?? '';
    $githubRepo = $_POST['github_repo'] ?? '';
    $description = $_POST['description'] ?? '';
    $phpVersion = $_POST['php_version'] ?? '7.0';
    $dbType = $_POST['db_type'] ?? 'Ninguna';
    $primaryColor = $_POST['primary_color'] ?? '#28a745';
    $secondaryColor = $_POST['secondary_color'] ?? '#20c997';
    $projectIcon = $_POST['project_icon'] ?? '🚀';
    
    // Carpetas
    $folders = array_filter($_POST['folders'] ?? []);
    
    // Extensiones PHP
    $extensions = $_POST['php_extensions'] ?? [];
    
    // Características
    $features = $_POST['features'] ?? [];
    
    // Archivos críticos
    $criticalFiles = array_filter($_POST['critical_files'] ?? []);
    
    // Datos iniciales JSON
    $jsonData = $_POST['json_data'] ?? '';
    
    // Generar el prompt
    $promptContent = "Crea un instalador PHP automático con estas características:\n\n";
    $promptContent .= "## PROYECTO:\n";
    $promptContent .= "- **Nombre**: $projectName\n";
    $promptContent .= "- **GitHub**: https://github.com/$githubUser/$githubRepo\n";
    $promptContent .= "- **Descripción**: $description\n\n";
    
    $promptContent .= "## ESTRUCTURA:\n";
    $promptContent .= "**Carpetas a crear:**\n```\n";
    foreach ($folders as $folder) {
        if (!empty($folder)) {
            $promptContent .= "$folder/\n";
        }
    }
    $promptContent .= "```\n\n";
    
    $promptContent .= "**Archivos principales**: Todos los archivos PHP del repositorio\n\n";
    
    $promptContent .= "**Base de datos**: $dbType\n\n";
    
    $promptContent .= "## COLORES:\n";
    $promptContent .= "- Principal: $primaryColor\n";
    $promptContent .= "- Secundario: $secondaryColor\n";
    $promptContent .= "- Icono: $projectIcon\n\n";
    
    $promptContent .= "## REQUISITOS:\n";
    $promptContent .= "- PHP $phpVersion+\n";
    if (!empty($extensions)) {
        foreach ($extensions as $ext) {
            $promptContent .= "- Extensión $ext\n";
        }
    }
    $promptContent .= "\n";
    
    if (!empty($features)) {
        $promptContent .= "## CARACTERÍSTICAS ESPECIALES:\n";
        foreach ($features as $feature) {
            $promptContent .= "- $feature\n";
        }
        $promptContent .= "\n";
    }
    
    if (!empty($criticalFiles)) {
        $promptContent .= "## ARCHIVOS CRÍTICOS (crear si falla descarga):\n";
        foreach ($criticalFiles as $file) {
            if (!empty($file)) {
                $promptContent .= "- $file\n";
            }
        }
        $promptContent .= "\n";
    }
    
    if (!empty($jsonData)) {
        $promptContent .= "## DATOS INICIALES JSON:\n```json\n";
        $promptContent .= $jsonData;
        $promptContent .= "\n```\n\n";
    }
    
    $promptContent .= "---\n\n";
    $promptContent .= "**INSTRUCCIÓN**: Genera un archivo `instalador.php` completo que:\n";
    $promptContent .= "1. Descargue los archivos desde GitHub\n";
    $promptContent .= "2. Cree todas las carpetas con permisos 755\n";
    $promptContent .= "3. Configure la base de datos\n";
    $promptContent .= "4. Tenga interfaz visual con los colores especificados\n";
    $promptContent .= "5. Incluya verificación de requisitos PHP\n";
    $promptContent .= "6. Se pueda auto-eliminar\n";
    $promptContent .= "7. Muestre progreso detallado de instalación\n";
    $promptContent .= "8. Maneje errores y tenga fallback local para archivos críticos\n";
    
    $promptGenerated = true;
}

// Descargar como TXT
if (isset($_POST['download'])) {
    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="prompt_instalador.txt"');
    echo $_POST['prompt_content'];
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Prompts para Auto-instaladores PHP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 40px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .form-section h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #667eea;
            outline: none;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
            background: white;
            padding: 8px 15px;
            border-radius: 20px;
            border: 2px solid #e1e8ed;
            cursor: pointer;
            transition: all 0.3s;
        }
        .checkbox-item:hover {
            border-color: #667eea;
        }
        .checkbox-item input[type="checkbox"] {
            cursor: pointer;
        }
        .checkbox-item.checked {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .add-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .add-button:hover {
            background: #5a6fd8;
        }
        .generate-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px 40px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            margin-top: 30px;
            transition: transform 0.3s;
        }
        .generate-button:hover {
            transform: translateY(-2px);
        }
        .output-section {
            position: sticky;
            top: 20px;
            height: fit-content;
        }
        .prompt-output {
            background: #2d3748;
            color: #f7fafc;
            padding: 30px;
            border-radius: 15px;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            max-height: 70vh;
            overflow-y: auto;
            margin-bottom: 20px;
            user-select: text;
            cursor: text;
            position: relative;
            transition: box-shadow 0.3s ease;
        }
        .prompt-output:hover {
            box-shadow: 0 0 0 2px #4299e1;
        }
        .prompt-output::selection {
            background: #4299e1;
            color: white;
        }
        .prompt-output::-moz-selection {
            background: #4299e1;
            color: white;
        }
        .prompt-output::-webkit-scrollbar {
            width: 8px;
        }
        .prompt-output::-webkit-scrollbar-track {
            background: #1a202c;
        }
        .prompt-output::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 4px;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .action-button {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        .copy-button {
            background: #48bb78;
            color: white;
        }
        .copy-button:hover {
            background: #38a169;
        }
        .select-button {
            background: #9b59b6;
            color: white;
        }
        .select-button:hover {
            background: #8e44ad;
        }
        .download-button {
            background: #4299e1;
            color: white;
        }
        .download-button:hover {
            background: #3182ce;
        }
        .reset-button {
            background: #f56565;
            color: white;
        }
        .reset-button:hover {
            background: #e53e3e;
        }
        .help-text {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        .color-input {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #e1e8ed;
        }
        .dynamic-list {
            margin-top: 10px;
        }
        .list-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .list-item input {
            flex: 1;
        }
        .remove-button {
            background: #f56565;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .success-message {
            background: #48bb78;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }
        @media (max-width: 968px) {
            .content {
                grid-template-columns: 1fr;
            }
            .output-section {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Generador de Prompts para Auto-instaladores</h1>
            <p>Crea prompts personalizados para generar instaladores PHP automáticos</p>
        </div>
        
        <div class="content">
            <div class="form-column">
                <form method="POST" id="generatorForm">
                    <!-- Información básica -->
                    <div class="form-section">
                        <h3>📋 Información del Proyecto</h3>
                        
                        <div class="form-group">
                            <label for="project_name">Nombre del Proyecto *</label>
                            <input type="text" id="project_name" name="project_name" required 
                                   placeholder="Mi Sistema Web" value="<?php echo $_POST['project_name'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="github_user">Usuario de GitHub *</label>
                                <input type="text" id="github_user" name="github_user" required 
                                       placeholder="miusuario" value="<?php echo $_POST['github_user'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="github_repo">Repositorio *</label>
                                <input type="text" id="github_repo" name="github_repo" required 
                                       placeholder="mi-proyecto" value="<?php echo $_POST['github_repo'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea id="description" name="description" rows="2" 
                                      placeholder="Sistema completo para gestionar..."><?php echo $_POST['description'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="project_icon">Icono del Proyecto</label>
                            <input type="text" id="project_icon" name="project_icon" 
                                   placeholder="🚀" value="<?php echo $_POST['project_icon'] ?? '🚀'; ?>" maxlength="2">
                            <p class="help-text">Usa un emoji para representar tu proyecto</p>
                        </div>
                    </div>
                    
                    <!-- Diseño -->
                    <div class="form-section">
                        <h3>🎨 Diseño Visual</h3>
                        
                        <div class="form-group">
                            <label for="primary_color">Color Primario</label>
                            <div class="color-input">
                                <input type="color" id="primary_color" name="primary_color" 
                                       value="<?php echo $_POST['primary_color'] ?? '#667eea'; ?>">
                                <input type="text" id="primary_color_text" 
                                       value="<?php echo $_POST['primary_color'] ?? '#667eea'; ?>" 
                                       pattern="#[0-9A-Fa-f]{6}" maxlength="7">
                                <div class="color-preview" id="primary_preview"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="secondary_color">Color Secundario</label>
                            <div class="color-input">
                                <input type="color" id="secondary_color" name="secondary_color" 
                                       value="<?php echo $_POST['secondary_color'] ?? '#764ba2'; ?>">
                                <input type="text" id="secondary_color_text" 
                                       value="<?php echo $_POST['secondary_color'] ?? '#764ba2'; ?>" 
                                       pattern="#[0-9A-Fa-f]{6}" maxlength="7">
                                <div class="color-preview" id="secondary_preview"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Requisitos técnicos -->
                    <div class="form-section">
                        <h3>⚙️ Requisitos Técnicos</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="php_version">Versión PHP Mínima</label>
                                <select id="php_version" name="php_version">
                                    <option value="7.0">PHP 7.0</option>
                                    <option value="7.2">PHP 7.2</option>
                                    <option value="7.4" selected>PHP 7.4</option>
                                    <option value="8.0">PHP 8.0</option>
                                    <option value="8.1">PHP 8.1</option>
                                    <option value="8.2">PHP 8.2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="db_type">Base de Datos</label>
                                <select id="db_type" name="db_type">
                                    <option value="Ninguna">Sin Base de Datos</option>
                                    <option value="JSON">JSON (archivos)</option>
                                    <option value="MySQL">MySQL/MariaDB</option>
                                    <option value="SQLite">SQLite</option>
                                    <option value="PostgreSQL">PostgreSQL</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Extensiones PHP Requeridas</label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="php_extensions[]" value="mysqli">
                                    <span>MySQLi</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="php_extensions[]" value="gd">
                                    <span>GD (Imágenes)</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="php_extensions[]" value="json" checked>
                                    <span>JSON</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="php_extensions[]" value="curl">
                                    <span>cURL</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="php_extensions[]" value="zip">
                                    <span>ZIP</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="php_extensions[]" value="mbstring">
                                    <span>Mbstring</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estructura de carpetas -->
                    <div class="form-section">
                        <h3>📁 Estructura de Carpetas</h3>
                        
                        <div class="form-group">
                            <label>Carpetas a crear (una por línea)</label>
                            <div id="folders-list" class="dynamic-list">
                                <div class="list-item">
                                    <input type="text" name="folders[]" placeholder="uploads/" value="uploads/">
                                </div>
                                <div class="list-item">
                                    <input type="text" name="folders[]" placeholder="data/" value="data/">
                                </div>
                                <div class="list-item">
                                    <input type="text" name="folders[]" placeholder="cache/">
                                </div>
                            </div>
                            <button type="button" class="add-button" onclick="addFolder()">+ Agregar Carpeta</button>
                        </div>
                    </div>
                    
                    <!-- Características -->
                    <div class="form-section">
                        <h3>✨ Características del Sistema</h3>
                        
                        <div class="form-group">
                            <label>Selecciona las características</label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Sistema de login/registro con roles">
                                    <span>Login/Registro</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Panel de administración completo">
                                    <span>Panel Admin</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Subida de archivos/imágenes">
                                    <span>Subida de Archivos</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Generación de PDFs">
                                    <span>Generar PDFs</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Envío de emails">
                                    <span>Envío Emails</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="API REST">
                                    <span>API REST</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Multi-idioma">
                                    <span>Multi-idioma</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Sistema de búsqueda avanzada">
                                    <span>Búsqueda Avanzada</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Dashboard con estadísticas">
                                    <span>Dashboard</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="features[]" value="Sistema de notificaciones">
                                    <span>Notificaciones</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Archivos críticos -->
                    <div class="form-section">
                        <h3>🔧 Archivos Críticos</h3>
                        <p class="help-text" style="margin-bottom: 15px;">
                            Archivos que deben crearse localmente si la descarga de GitHub falla
                        </p>
                        
                        <div class="form-group">
                            <label>Archivos críticos</label>
                            <div id="critical-files-list" class="dynamic-list">
                                <div class="list-item">
                                    <input type="text" name="critical_files[]" placeholder="includes/config.php">
                                </div>
                                <div class="list-item">
                                    <input type="text" name="critical_files[]" placeholder="includes/database.php">
                                </div>
                            </div>
                            <button type="button" class="add-button" onclick="addCriticalFile()">+ Agregar Archivo</button>
                        </div>
                    </div>
                    
                    <!-- Datos iniciales JSON -->
                    <div class="form-section">
                        <h3>📊 Datos Iniciales (JSON)</h3>
                        
                        <div class="form-group">
                            <label for="json_data">Estructura JSON inicial (opcional)</label>
                            <textarea id="json_data" name="json_data" rows="8" 
                                      placeholder='// Ejemplo:
{
  "users": [],
  "config": {
    "site_name": "Mi Sitio",
    "version": "1.0"
  }
}'><?php echo $_POST['json_data'] ?? ''; ?></textarea>
                            <p class="help-text">Define la estructura inicial de tus archivos JSON</p>
                        </div>
                    </div>
                    
                    <button type="submit" name="generate" class="generate-button">
                        🚀 Generar Prompt
                    </button>
                </form>
            </div>
            
            <div class="output-section">
                <h3 style="margin-bottom: 20px;">📝 Prompt Generado</h3>
                
                <div id="success-message" class="success-message">
                    ✅ ¡Prompt copiado al portapapeles!
                </div>
                
                <?php if ($promptGenerated): ?>
                    <?php 
                    // Detectar si estamos en HTTPS
                    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                    ?>
                    
                    <?php if (!$isHttps): ?>
                        <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 10px; font-size: 14px;">
                            ⚠️ <strong>Nota:</strong> Estás usando HTTP. El botón copiar podría no funcionar. Usa "Seleccionar Todo" y luego Ctrl+C.
                            <button onclick="testClipboard()" style="margin-left: 10px; padding: 2px 8px; background: #856404; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">Probar Portapapeles</button>
                        </div>
                    <?php endif; ?>
        
        // Detectar evento de copia global
        document.addEventListener('copy', function(e) {
            const selection = window.getSelection();
            if (selection.toString().length > 100) { // Si se copió texto largo
                const message = document.getElementById('success-message');
                if (message) {
                    message.textContent = '✅ ¡Contenido copiado al portapapeles!';
                    message.style.display = 'block';
                    setTimeout(() => {
                        message.style.display = 'none';
                        message.textContent = '✅ ¡Prompt copiado al portapapeles!';
                    }, 3000);
                }
            }
        });
                    
                    <div class="prompt-output" id="promptOutput" title="Triple-click para seleccionar todo"><?php echo htmlspecialchars($promptContent); ?></div>
                    
                    <div class="actions">
                        <button type="button" class="action-button copy-button" onclick="copyPrompt()">
                            📋 Copiar
                        </button>
                        <button type="button" class="action-button select-button" onclick="selectPrompt()">
                            📝 Seleccionar Todo
                        </button>
                        <form method="POST" style="flex: 1;">
                            <input type="hidden" name="prompt_content" value="<?php echo htmlspecialchars($promptContent); ?>">
                            <button type="submit" name="download" class="action-button download-button" style="width: 100%;">
                                💾 Descargar TXT
                            </button>
                        </form>
                        <button type="button" class="action-button reset-button" onclick="resetForm()">
                            🔄 Limpiar
                        </button>
                    </div>
                <?php else: ?>
                    <div class="prompt-output" style="text-align: center; color: #718096;">
                        <p style="margin-top: 100px;">
                            👈 Completa el formulario y haz clic en<br>
                            "Generar Prompt" para ver el resultado aquí
                        </p>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <h4 style="margin-bottom: 10px; color: #333;">💡 Cómo usar el generador:</h4>
                        <ol style="margin-left: 20px; color: #666; font-size: 14px; line-height: 1.8;">
                            <li>Completa el formulario con los datos de tu proyecto</li>
                            <li>Haz clic en "Generar Prompt"</li>
                                                                <li>Copia el prompt generado usando:
                                <ul style="margin-top: 5px;">
                                    <li>📋 Botón "Copiar" (funciona mejor en HTTPS)</li>
                                    <li>📝 Botón "Seleccionar Todo" + Ctrl+C (siempre funciona)</li>
                                    <li>💾 Botón "Descargar TXT" para guardarlo</li>
                                    <li>🖱️ Triple-click en el texto para seleccionarlo</li>
                                </ul>
                            </li>
                            <li>Pega el prompt en el chat para obtener tu instalador</li>
                        </ol>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Sincronizar inputs de color
        document.getElementById('primary_color').addEventListener('input', function(e) {
            document.getElementById('primary_color_text').value = e.target.value;
            document.getElementById('primary_preview').style.backgroundColor = e.target.value;
        });
        
        document.getElementById('primary_color_text').addEventListener('input', function(e) {
            if (e.target.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                document.getElementById('primary_color').value = e.target.value;
                document.getElementById('primary_preview').style.backgroundColor = e.target.value;
            }
        });
        
        document.getElementById('secondary_color').addEventListener('input', function(e) {
            document.getElementById('secondary_color_text').value = e.target.value;
            document.getElementById('secondary_preview').style.backgroundColor = e.target.value;
        });
        
        document.getElementById('secondary_color_text').addEventListener('input', function(e) {
            if (e.target.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                document.getElementById('secondary_color').value = e.target.value;
                document.getElementById('secondary_preview').style.backgroundColor = e.target.value;
            }
        });
        
        // Inicializar colores
        document.getElementById('primary_preview').style.backgroundColor = document.getElementById('primary_color').value;
        document.getElementById('secondary_preview').style.backgroundColor = document.getElementById('secondary_color').value;
        
        // Agregar carpeta
        function addFolder() {
            const list = document.getElementById('folders-list');
            const item = document.createElement('div');
            item.className = 'list-item';
            item.innerHTML = `
                <input type="text" name="folders[]" placeholder="nueva-carpeta/">
                <button type="button" class="remove-button" onclick="removeItem(this)">✕</button>
            `;
            list.appendChild(item);
        }
        
        // Agregar archivo crítico
        function addCriticalFile() {
            const list = document.getElementById('critical-files-list');
            const item = document.createElement('div');
            item.className = 'list-item';
            item.innerHTML = `
                <input type="text" name="critical_files[]" placeholder="archivo.php">
                <button type="button" class="remove-button" onclick="removeItem(this)">✕</button>
            `;
            list.appendChild(item);
        }
        
        // Remover item de lista
        function removeItem(button) {
            button.parentElement.remove();
        }
        
        // Función para probar el portapapeles
        function testClipboard() {
            const testText = 'Prueba de portapapeles';
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(testText).then(function() {
                    alert('✅ El portapapeles funciona correctamente. Se copió: "' + testText + '"');
                }).catch(function(err) {
                    alert('❌ El portapapeles no funciona en este navegador/contexto. Error: ' + err.message);
                });
            } else {
                alert('❌ El portapapeles no está disponible. Usa HTTPS o el método alternativo.');
            }
        }
        
        // Detectar si es móvil
        function isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }
        
        // Copiar prompt
        function copyPrompt() {
            const promptElement = document.getElementById('promptOutput');
            if (!promptElement) {
                alert('No hay prompt generado para copiar');
                return;
            }
            
            const promptText = promptElement.textContent;
            
            // Log para debugging
            console.log('Intentando copiar texto de longitud:', promptText.length);
            console.log('Es móvil:', isMobile());
            console.log('Es HTTPS:', window.isSecureContext);
            
            // En móviles, siempre usar selección manual
            if (isMobile()) {
                selectPrompt();
                alert('Texto seleccionado. Mantén presionado y selecciona "Copiar"');
                return;
            }
            
            // Método moderno (requiere HTTPS)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(promptText).then(function() {
                    console.log('Copiado exitosamente con clipboard API');
                    const message = document.getElementById('success-message');
                    message.style.display = 'block';
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 3000);
                }).catch(function(err) {
                    console.error('Error con clipboard API:', err);
                    // Si falla, usar el método alternativo
                    copyUsingTextarea(promptText);
                });
            } else {
                console.log('Usando método alternativo (no HTTPS o clipboard no disponible)');
                // Método alternativo para HTTP o navegadores antiguos
                copyUsingTextarea(promptText);
            }
        }
        
        // Método alternativo de copiar
        function copyUsingTextarea(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-999999px';
            textarea.style.top = '-999999px';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            
            try {
                const successful = document.execCommand('copy');
                textarea.remove();
                
                if (successful) {
                    const message = document.getElementById('success-message');
                    message.style.display = 'block';
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 3000);
                } else {
                    // Fallback final: mostrar el texto en un modal para copiar manualmente
                    showCopyModal(text);
                }
            } catch (err) {
                console.error('Error al copiar:', err);
                textarea.remove();
                showCopyModal(text);
            }
        }
        
        // Modal de copia manual como último recurso
        function showCopyModal(text) {
            // Crear overlay
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 9999;
            `;
            
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                z-index: 10000;
                max-width: 90%;
                max-height: 80vh;
            `;
            
            modal.innerHTML = `
                <h3 style="margin-bottom: 10px;">📋 Copiar Manualmente</h3>
                <p style="margin-bottom: 10px; color: #666;">El copiado automático no funcionó. Selecciona todo el texto con Ctrl+A y cópialo con Ctrl+C:</p>
                <textarea style="width: 400px; height: 300px; padding: 10px; border: 2px solid #667eea; border-radius: 5px; font-family: monospace; font-size: 12px;" readonly>${text}</textarea>
                <div style="margin-top: 10px; text-align: center;">
                    <button onclick="document.body.removeChild(this.parentElement.parentElement.previousSibling); document.body.removeChild(this.parentElement.parentElement);" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">✅ Cerrar</button>
                </div>
            `;
            
            document.body.appendChild(overlay);
            document.body.appendChild(modal);
            modal.querySelector('textarea').select();
            
            // Cerrar al hacer clic en el overlay
            overlay.onclick = function() {
                document.body.removeChild(overlay);
                document.body.removeChild(modal);
            };
        }
        
        // Seleccionar todo el texto del prompt
        function selectPrompt() {
            const promptElement = document.getElementById('promptOutput');
            if (!promptElement) {
                alert('No hay prompt generado para seleccionar');
                return;
            }
            
            // Crear un rango y seleccionar el texto
            if (window.getSelection && document.createRange) {
                const selection = window.getSelection();
                const range = document.createRange();
                range.selectNodeContents(promptElement);
                selection.removeAllRanges();
                selection.addRange(range);
                
                // Resaltar visualmente
                promptElement.style.boxShadow = '0 0 0 3px #48bb78';
                setTimeout(() => {
                    promptElement.style.boxShadow = '';
                }, 2000);
                
                // Mensaje de ayuda
                const message = document.getElementById('success-message');
                message.textContent = '✅ Texto seleccionado. Ahora presiona Ctrl+C (o Cmd+C en Mac) para copiar';
                message.style.display = 'block';
                setTimeout(() => {
                    message.style.display = 'none';
                    message.textContent = '✅ ¡Prompt copiado al portapapeles!';
                }, 4000);
            }
        }
        
        // Limpiar formulario
        function resetForm() {
            if (confirm('¿Estás seguro de que quieres limpiar el formulario?')) {
                document.getElementById('generatorForm').reset();
                window.location.href = window.location.pathname;
            }
        }
        
        // Estilo de checkboxes
        document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.classList.add('checked');
                } else {
                    this.parentElement.classList.remove('checked');
                }
            });
            
            // Estado inicial
            if (checkbox.checked) {
                checkbox.parentElement.classList.add('checked');
            }
        });
        
        // Configurar eventos del prompt si está presente
        <?php if ($promptGenerated): ?>
        window.addEventListener('load', function() {
            const promptOutput = document.getElementById('promptOutput');
            if (promptOutput) {
                // Triple-click para seleccionar todo
                let clickCount = 0;
                let clickTimer = null;
                
                promptOutput.addEventListener('click', function(e) {
                    clickCount++;
                    
                    if (clickCount === 3) {
                        selectPrompt();
                        clickCount = 0;
                    }
                    
                    clearTimeout(clickTimer);
                    clickTimer = setTimeout(() => {
                        clickCount = 0;
                    }, 500);
                });
                
                // Detectar cuando se copia el texto
                promptOutput.addEventListener('copy', function(e) {
                    const message = document.getElementById('success-message');
                    message.textContent = '✅ ¡Texto copiado al portapapeles exitosamente!';
                    message.style.display = 'block';
                    setTimeout(() => {
                        message.style.display = 'none';
                        message.textContent = '✅ ¡Prompt copiado al portapapeles!';
                    }, 3000);
                });
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
