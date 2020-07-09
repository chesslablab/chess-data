import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { connect as databaseConnect } from 'actions/databaseActions';
import { disconnect as databaseDisconnect } from 'actions/databaseActions';
import { makeStyles } from '@material-ui/core/styles';
import Collapse from '@material-ui/core/Collapse';
import Divider from '@material-ui/core/Divider';
import { Link } from 'react-router-dom';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import ExpandLess from '@material-ui/icons/ExpandLess';
import ExpandMore from '@material-ui/icons/ExpandMore';
import StorageIcon from '@material-ui/icons/Storage';
import VerticalAlignCenterIcon from '@material-ui/icons/VerticalAlignCenter';
import WidgetsIcon from '@material-ui/icons/Widgets';
import FlashAutoIcon from '@material-ui/icons/FlashAuto';
import SecurityIcon from '@material-ui/icons/Security';
import GrainIcon from '@material-ui/icons/Grain';
import PowerIcon from '@material-ui/icons/Power';
import PowerOffIcon from '@material-ui/icons/PowerOff';

const useStyles = makeStyles((theme) => ({
  nested: {
    paddingLeft: theme.spacing(4),
  },
}));

const MainNav = () => {
  const classes = useStyles();
  const [open, setOpen] = React.useState(false);

  const databaseReducer = useSelector(state => state.databaseReducer);
  const dispatch = useDispatch();

  const handleClickDatabaseSubmenu = () => {
    setOpen(!open);
  };

  const handleClickConnect = (e) => {
    e.preventDefault();
    dispatch(databaseConnect());
  };

  const handleClickDisconnect = (e) => {
    e.preventDefault();
    dispatch(databaseDisconnect());
  };

  return (
    <div>
      <ListItem button onClick={handleClickDatabaseSubmenu}>
        <ListItemIcon>
          <StorageIcon />
        </ListItemIcon>
        <ListItemText secondary="Database" />
        {open ? <ExpandLess /> : <ExpandMore />}
      </ListItem>
      <Collapse in={open} timeout="auto" unmountOnExit>
        <List component="div" disablePadding>
          {
            databaseReducer.connected
              ? <ListItem button className={classes.nested} onClick={handleClickDisconnect}>
                  <ListItemIcon>
                    <PowerOffIcon />
                  </ListItemIcon>
                  <ListItemText secondary="Disconnect" />
                </ListItem>
              : <ListItem button className={classes.nested} onClick={handleClickConnect}>
                  <ListItemIcon>
                    <PowerIcon />
                  </ListItemIcon>
                  <ListItemText secondary="Connect" />
                </ListItem>
          }
        </List>
      </Collapse>
      <Divider />
      <ListItem button component={Link} to="/attack">
        <ListItemIcon>
          <FlashAutoIcon />
        </ListItemIcon>
        <ListItemText secondary="Attack" />
      </ListItem>
      <ListItem button component={Link} to="/center">
        <ListItemIcon>
          <VerticalAlignCenterIcon />
        </ListItemIcon>
        <ListItemText secondary="Center" />
      </ListItem>
      <ListItem button component={Link} to="/king-safety">
        <ListItemIcon>
          <SecurityIcon />
        </ListItemIcon>
        <ListItemText secondary="King safety" />
      </ListItem>
      <ListItem button component={Link} to="/material">
        <ListItemIcon>
          <GrainIcon />
        </ListItemIcon>
        <ListItemText secondary="Material" />
      </ListItem>
      <ListItem button component={Link} to="/space">
        <ListItemIcon>
          <WidgetsIcon />
        </ListItemIcon>
        <ListItemText secondary="Space" />
      </ListItem>
    </div>
  );
}

export default MainNav;
