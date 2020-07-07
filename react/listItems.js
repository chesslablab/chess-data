import React from 'react';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import VerticalAlignCenterIcon from '@material-ui/icons/VerticalAlignCenter';
import WidgetsIcon from '@material-ui/icons/Widgets';
import FlashAutoIcon from '@material-ui/icons/FlashAuto';
import SecurityIcon from '@material-ui/icons/Security';
import GrainIcon from '@material-ui/icons/Grain';
import PanoramaIcon from '@material-ui/icons/Panorama';

export const mainListItems = (
  <div>
    <ListItem button>
      <ListItemIcon>
        <FlashAutoIcon />
      </ListItemIcon>
      <ListItemText primary="Attack" />
    </ListItem>
    <ListItem button>
      <ListItemIcon>
        <VerticalAlignCenterIcon />
      </ListItemIcon>
      <ListItemText primary="Center" />
    </ListItem>
    <ListItem button>
      <ListItemIcon>
        <SecurityIcon />
      </ListItemIcon>
      <ListItemText primary="King safety" />
    </ListItem>
    <ListItem button>
      <ListItemIcon>
        <GrainIcon />
      </ListItemIcon>
      <ListItemText primary="Material" />
    </ListItem>
    <ListItem button>
      <ListItemIcon>
        <PanoramaIcon />
      </ListItemIcon>
      <ListItemText primary="Space" />
    </ListItem>
    <ListItem button>
      <ListItemIcon>
        <WidgetsIcon />
      </ListItemIcon>
      <ListItemText primary="Square" />
    </ListItem>
  </div>
);
