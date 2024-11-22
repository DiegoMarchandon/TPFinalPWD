<?php
//ABM MIO
class ABMUsuario {

    /**
     * Función utilizada en caso que esperemos un arreglo de un solo elemento.
     * Llama a Buscar, convierte el obj del indice 0 a arreglo y lo retorna.
     * Si retorna un arreglo vacío, devuelve null.
     * @return array|null
     */
    public function arrayOnull($arrAsoc) {
        $objetoOnull = $this->buscar($arrAsoc);
        $element = null;

        if (count($objetoOnull) === 1) {
            $element = dismount($objetoOnull[0]);
        }

        return $element;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $datos
     * @return bool
     */
    public function abm($datos) {
        $resp = false;
        if ($datos['accion'] == 'editar') {
            if ($this->modificacion($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'borrar') {
            if ($this->baja($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'nuevo') {
            if ($this->alta($datos)) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Usuario
     */
    private function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('usnombre', $param) && array_key_exists('uspass', $param) && array_key_exists('usmail', $param) && array_key_exists('usdeshabilitado', $param)) {
            $obj = new Usuario();
            $obj->setear($param['idusuario'], $param['usnombre'], $param['uspass'], $param['usmail'], $param['usdeshabilitado']);
        }

        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Usuario
     */
    private function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idusuario'])) {
            $obj = new Usuario();
            $obj->setIdusuario($param['idusuario']);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        $resp = false;
        if (isset($param['idusuario']))
            $resp = true;
        return $resp;
    }

    /**
     * permite ingresar un objeto
     * @param array $param
     */
    public function alta($param) {
        $resp = false;
        $param['idusuario'] = null; // Establecer idusuario en null
        $param['usdeshabilitado']=null;

        $objUsuario = $this->cargarObjeto($param);
        if ($objUsuario != null and $objUsuario->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuario = $this->cargarObjetoConClave($param);
            if ($objUsuario != null and $objUsuario->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null and $objUsuario->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario'])) $where .= " and idusuario ='" . $param['idusuario'] . "'";
            if (isset($param['usnombre'])) $where .= " and usnombre ='" . $param['usnombre'] . "'";
            if (isset($param['uspass'])) $where .= " and uspass ='" . $param['uspass'] . "'";
            if (isset($param['usmail'])) $where .= " and usmail ='" . $param['usmail'] . "'";
            if (isset($param['usdeshabilitado'])) $where .= " and usdeshabilitado ='" . $param['usdeshabilitado'] . "'";
        }
        $usuario = new Usuario();
        $arreglo = $usuario->listar($where);
        return $arreglo;
    }

    /**
     * Si recibe como parámetro un arreglo asociativo clave-valor, llama al Buscar  
     * y retorna un arreglo con arreglos asociativos. 
     * Si recibe como parámetro un objeto, convierte sus propiedades en un arreglo asociativo.
     * @param array|object $param
     * @return array  
     */
    public function buscarArray($param) {
        $arreglo = [];
        if (is_object($param)) {
            $arreglo = dismount($param);
        } else {
            $arreglo = convert_array($this->buscar($param));
        }
        return $arreglo;
    }

    /**
    * Permite borrar un rol de un usuario.
    * 
    * Esta función elimina la relación entre un usuario y un rol específico.
    * Requiere que se proporcionen los identificadores del usuario y del rol.
    * 
    * @param array $param Array asociativo con las claves 'idusuario' y 'idrol'.
    * @return boolean Devuelve true si la eliminación fue exitosa, false en caso contrario.
    */
    public function borrar_rol($param) {
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($param['idusuario'], $param['idrol']);
            if ($objUsuarioRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
    * Permite agregar un rol a un usuario.
    * 
    * Esta función crea una relación entre un usuario y un rol específico.
    * Requiere que se proporcionen los identificadores del usuario y del rol.
    * 
    * @param array $param Array asociativo con las claves 'idusuario' y 'idrol'.
    * @return boolean Devuelve true si la inserción fue exitosa, false en caso contrario.
    */
    public function alta_rol($param) {
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($param['idusuario'], $param['idrol']);
            if ($objUsuarioRol->insertar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
    * Permite obtener los roles de un usuario.
    * 
    * Esta función devuelve una lista de roles asociados a un usuario específico.
    * Requiere que se proporcione el identificador del usuario.
    * 
    * @param array $param Array asociativo con las claves 'idusuario' y/o 'idrol'.
    * @return array Devuelve un array de objetos UsuarioRol.
    */
    public function darRoles($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario'])) {
                $where .= " and idusuario =" . $param['idusuario'];
            }
            if (isset($param['idrol'])) {
                $where .= " and idrol ='" . $param['idrol'] . "'";
            }
        }
        $obj = new UsuarioRol();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

    public function separarUsuariosHabilitadosYDeshabilitados() {
        $usuarios = $this->buscar(null);

        $usuariosHabilitados = [];
        $usuariosDeshabilitados = [];

        foreach ($usuarios as $usuario) {
            if ($usuario->getUsDeshabilitado() === '0000-00-00 00:00:00') {
                $usuariosHabilitados[] = $usuario;
            } else {
                $usuariosDeshabilitados[] = $usuario;
            }
        }

        return [
            'habilitados' => $usuariosHabilitados,
            'deshabilitados' => $usuariosDeshabilitados
        ];
    }
    /**
     * Registrar un nuevo usuario
     * @param array $datos
     * @return array
     */
    public function registrarUsuario($datos) {
        $response = [
            'status' => 'default',
            'message' => 'Parte inicial del action'
        ];

        // Verificar si el nombre de usuario ya existe
        $usuarioExistente = $this->buscar(['usnombre' => $datos['usnombre']]);
        if (count($usuarioExistente) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre de usuario no está disponible.';
            return $response;
        }

        // Verificar si el correo electrónico ya existe
        $emailExistente = $this->buscar(['usmail' => $datos['usmail']]);
        if (count($emailExistente) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'El correo electrónico ya está asociado a una cuenta.';
            return $response;
        }

        // Obtener la contraseña hasheada
        $hashedPassword = $datos['uspass'];

        $param = [
            'usnombre' => $datos['usnombre'],
            'uspass' => $hashedPassword, // contraseña hasheada
            'usmail' => $datos['usmail'],
        ];

        if ($this->alta($param)) {
            // Obtener el ID del usuario recien creado
            $usuarioNuevo = $this->buscar(['usnombre' => $datos['usnombre']]);
            $idUsuario = $usuarioNuevo[0]->getIdUsuario();

            // Asignar el rol de "Usuario" por defecto
            $abmUsuarioRol = new ABMUsuarioRol();
            $abmUsuarioRol->alta(['idusuario' => $idUsuario, 'idrol' => 3]); // el id 3 es de "Cliente" que se le asignara a todos los que se registren por defecto

            $response['status'] = 'success';
            $response['message'] = 'Registro exitoso.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al registrar el usuario.';
        }

        return $response;
    }
    /**
     * Actualizar los datos de un usuario
     * @param array $datos
     * @return array
     */
    public function actualizarUsuario($datos) {
        $response = [
            'status' => 'default',
            'message' => 'Parte inicial del action'
        ];

        // Buscar el usuario actual por nombre de usuario
        $userActual = $this->buscarArray(['usnombre' => $datos['nombreActual']]);

        if (count($userActual) > 0) {
            $userActual = $userActual[0];

            // Si el campo está vacío, se mantiene el nombre actual del usuario
            $nombreUsuario = $datos['nuevoNombre'] === '' ? $userActual['usnombre'] : $datos['nuevoNombre'];

            // Si el campo de la contraseña está vacío, se mantiene la contraseña actual del usuario
            $hashedPassword = $datos['nuevaContraseña'] === '' || $datos['nuevaContraseñaConfirm'] === '' ? $userActual['uspass'] : $datos['nuevaContraseña'];

            // Si el campo del email está vacío, se mantiene el email actual del usuario
            $email = $datos['nuevoEmail'] === '' ? $userActual['usmail'] : $datos['nuevoEmail'];

            $param = [
                'idusuario' => $userActual['idusuario'],
                'usnombre' => $nombreUsuario,
                'uspass' => $hashedPassword,
                'usmail' => $email,
                'usdeshabilitado' => $userActual['usdeshabilitado']
            ];

            if ($this->modificacion($param)) {
                $response['status'] = 'success';
                $response['message'] = 'Actualización exitosa';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al actualizar el usuario.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Usuario no encontrado.';
        }

        return $response;
    }


}
?>