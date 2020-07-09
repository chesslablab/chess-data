import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { useForm } from "react-hook-form";
import { makeStyles } from '@material-ui/core/styles';
import { accept as dbAccept } from 'actions/dbActions';
import { cancel as dbCancel } from 'actions/dbActions';
import Modal from '@material-ui/core/Modal';
import Backdrop from '@material-ui/core/Backdrop';
import Fade from '@material-ui/core/Fade';
import { Button, ButtonGroup, TextField } from '@material-ui/core';

const useStyles = makeStyles((theme) => ({
  modal: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
  },
  paper: {
    width: 400,
    backgroundColor: theme.palette.background.paper,
    border: '2px solid #000',
    boxShadow: theme.shadows[5],
    padding: theme.spacing(2, 4, 3),
  },
}));

export default function DbConnect() {
  const classes = useStyles();
  const dbReducer = useSelector(state => state.dbReducer);
  const dispatch = useDispatch();
  const { register, handleSubmit } = useForm();

  const handleClickCancel = () => {
    dispatch(dbCancel());
  };

  const onSubmitForm = (data) => {
    dispatch(dbAccept(data));
  };

  return (
    <div>
      <Modal
        aria-labelledby="transition-modal-title"
        aria-describedby="transition-modal-description"
        className={classes.modal}
        open={dbReducer.modal.open}
        onClose={handleClickCancel}
        closeAfterTransition
        BackdropComponent={Backdrop}
        BackdropProps={{
          timeout: 500,
        }}
      >
        <Fade in={dbReducer.modal.open}>
          <div className={classes.paper}>
            <form onSubmit={handleSubmit(onSubmitForm)}>
              <TextField
                required
                fullWidth
                id="host"
                label="Host"
                name="host"
                margin="normal"
                InputLabelProps={{ shrink: true }}
                inputRef={register}
              />
              <TextField
                required
                fullWidth
                id="port"
                label="Port"
                name="port"
                type="number"
                defaultValue="3306"
                margin="normal"
                InputLabelProps={{ shrink: true }}
                inputRef={register}
              />
              <TextField
                required
                fullWidth
                id="username"
                label="Username"
                name="username"
                margin="normal"
                InputLabelProps={{ shrink: true }}
                inputRef={register}
              />
              <TextField
                type="password"
                required
                fullWidth
                id="password"
                label="Password"
                name="password"
                margin="normal"
                InputLabelProps={{ shrink: true }}
                inputRef={register}
              />
              <ButtonGroup
                style={{ marginTop: 10, marginBottom: 10 }}
                size="small"
                fullWidth
              >
                <Button color="primary" type="submit">Connect</Button>
                <Button color="secondary" onClick={ (e) => handleClickCancel(e) }>Cancel</Button>
              </ButtonGroup>
            </form>
          </div>
        </Fade>
      </Modal>
    </div>
  );
}
