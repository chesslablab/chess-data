import React from 'react';
import { Link } from 'react-router-dom'
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import VerticalAlignCenterIcon from '@material-ui/icons/VerticalAlignCenter';
import WidgetsIcon from '@material-ui/icons/Widgets';
import FlashAutoIcon from '@material-ui/icons/FlashAuto';
import SecurityIcon from '@material-ui/icons/Security';
import GrainIcon from '@material-ui/icons/Grain';

export const MainNav = (
  <div>
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
